<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPrintJob;
use App\Models\CreditTransaction;
use App\Models\PrintJob;
use App\Services\FileConverter;
use App\Services\KioskCreditService;
use App\Services\KioskSessionLock;
use App\Services\PageSelectionParser;
use App\Services\PdfPageCounter;
use App\Services\PdfPageExtractor;
use App\Services\PdfPreviewGenerator;
use App\Services\PrintJobFactory;
use App\Services\PrintJobStateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class KioskController extends Controller
{
    public function home(
        KioskSessionLock $kioskSessionLock
    ): RedirectResponse|View {
        $activeJobUuid = $kioskSessionLock->activeJobUuid();

        if ($activeJobUuid) {
            $printJob = PrintJob::query()
                ->where('uuid', $activeJobUuid)
                ->first();

            if ($printJob) {
                return redirect()->route('kiosk.preview', $printJob);
            }

            $kioskSessionLock->unlock();
        }

        return view('kiosk.home');
    }

    public function upload(): View
    {
        $printJobs = PrintJob::query()
            ->where('status', 'uploaded')
            ->latest()
            ->take(20)
            ->get();

        return view('kiosk.upload', [
            'printJobs' => $printJobs,
        ]);
    }

    public function selectUploadedFile(
        PrintJob $printJob,
        KioskSessionLock $kioskSessionLock
    ): RedirectResponse {
        if ($printJob->status !== 'uploaded') {
            return redirect()->route('kiosk.upload');
        }

        $printJob->update([
            'status' => 'pending_payment',
            'paid_amount' => 0,
        ]);

        $kioskSessionLock->lock($printJob);

        $this->refreshExpiration($printJob);

        return redirect()->route('kiosk.preview', $printJob);
    }

    public function store(
        Request $request,
        PrintJobFactory $printJobFactory,
        KioskSessionLock $kioskSessionLock
    ): RedirectResponse {
        $validated = $request->validate(
            $this->uploadValidationRules(),
            $this->uploadValidationMessages()
        );

        try {
            $printJob = $printJobFactory->createFromUploadedFile(
                $validated['document']
            );
        } catch (Throwable $exception) {
            logger()->error('Kiosk upload failed', [
                'message' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            return back()->withErrors([
                'document' => $exception->getMessage(),
            ]);
        }

        $kioskSessionLock->lock($printJob);

        return redirect()->route('kiosk.preview', $printJob);
    }

    public function payment(
        PrintJob $printJob,
        KioskCreditService $creditService
    ): RedirectResponse|View {
        if (
            $printJob->status === 'cancelled' ||
            (
                $printJob->expires_at &&
                now()->greaterThan($printJob->expires_at)
            )
        ) {
            return redirect()->route('kiosk.home');
        }

        $creditService->useFor($printJob);

        $printJob->refresh();

        $this->refreshExpiration($printJob);

        return view('kiosk.payment', [
            'printJob' => $printJob,
        ]);
    }

    public function addCoin(PrintJob $printJob): RedirectResponse
    {
        if ($printJob->status !== 'pending_payment') {
            return redirect()->route('kiosk.payment', $printJob);
        }

        $printJob->increment('paid_amount');

        $printJob->refresh();

        if ($printJob->paid_amount >= $printJob->total_amount) {
            $printJob->update([
                'status' => 'paid',
            ]);
        }

        $this->refreshExpiration($printJob);

        return redirect()->route('kiosk.payment', $printJob);
    }

    public function addCredit(
        PrintJob $printJob,
        int $amount
    ): RedirectResponse {
        if ($printJob->status !== 'pending_payment') {
            return redirect()->route('kiosk.payment', $printJob);
        }

        if (! in_array($amount, [1, 5, 10], true)) {
            abort(404);
        }

        $printJob->increment('paid_amount', $amount);

        CreditTransaction::create([
            'print_job_id' => $printJob->id,
            'amount' => $amount,
            'source' => 'dummy',
        ]);

        $printJob->refresh();

        if ($printJob->paid_amount >= $printJob->total_amount) {
            $printJob->update([
                'status' => 'paid',
            ]);
        }

        $this->refreshExpiration($printJob);

        return redirect()->route('kiosk.payment', $printJob);
    }

    public function print(
        PrintJob $printJob,
        PrintJobStateService $stateService
    ): RedirectResponse {
        abort_if(
            $printJob->expires_at &&
            now()->greaterThan($printJob->expires_at),
            403
        );

        abort_if($printJob->status !== 'paid', 403);

        try {
            $stateService->transition($printJob, 'queued');

            ProcessPrintJob::dispatch($printJob);
        } catch (RuntimeException) {
            abort(403);
        }

        return redirect()->route('kiosk.status', $printJob);
    }

    public function printing(PrintJob $printJob): View
    {
        return view('kiosk.printing', [
            'printJob' => $printJob,
        ]);
    }

    public function status(PrintJob $printJob): RedirectResponse|View
    {
        if (
            $printJob->status === 'cancelled' ||
            (
                $printJob->expires_at &&
                now()->greaterThan($printJob->expires_at)
            )
        ) {
            return redirect()->route('kiosk.home');
        }

        $this->refreshExpiration($printJob);

        return view('kiosk.status', [
            'printJob' => $printJob,
        ]);
    }

    public function preview(PrintJob $printJob): RedirectResponse|View
    {
        if (
            $printJob->status === 'cancelled' ||
            (
                $printJob->expires_at &&
                now()->greaterThan($printJob->expires_at)
            )
        ) {
            return redirect()->route('kiosk.home');
        }

        $this->refreshExpiration($printJob);

        $previewUrl = URL::temporarySignedRoute(
            'kiosk.preview-file',
            now()->addMinutes(5),
            [
                'printJob' => $printJob,
            ]
        );

        return view('kiosk.preview', [
            'printJob' => $printJob,
            'previewUrl' => $previewUrl,
        ]);
    }

    public function confirm(PrintJob $printJob): RedirectResponse
    {
        if ($printJob->status === 'uploaded') {
            $printJob->update([
                'status' => 'pending_payment',
            ]);
        }

        $this->refreshExpiration($printJob);

        return redirect()->route('kiosk.payment', $printJob);
    }

    public function updateSettings(
        Request $request,
        PrintJob $printJob,
        PageSelectionParser $parser,
        PdfPageExtractor $extractor,
        PdfPreviewGenerator $previewGenerator,
        FileConverter $fileConverter,
        PdfPageCounter $pdfPageCounter
    ): RedirectResponse {
        $validated = $request->validate([
            'page_selection' => ['nullable', 'string'],
            'print_mode' => ['required', 'in:black,color'],
            'orientation' => ['required', 'in:portrait,landscape'],
            'paper_size' => ['required', 'in:short,long'],
        ]);

        $selection = strtolower(
            trim($validated['page_selection'] ?? '')
        );

        if ($selection === '' || $selection === 'all') {
            $selection = 'all';
        }

        try {
            $workingPdf = $this->buildLayoutAwarePdf(
                printJob: $printJob,
                orientation: $validated['orientation'],
                paperSize: $validated['paper_size'],
                fileConverter: $fileConverter
            );

            $pages = $pdfPageCounter->count($workingPdf);

            if ($selection === 'all') {
                $selectedPages = range(1, $pages);
                $relativeFilteredPath = null;
                $pdfForPreview = $workingPdf;
            } else {
                $selectedPages = $parser->parse(
                    $selection,
                    $pages
                );

                $filteredPdf = $extractor->extract(
                    $workingPdf,
                    $selection
                );

                $relativeFilteredPath =
                    'print-jobs/filtered/' .
                    basename($filteredPdf);

                $pdfForPreview = $filteredPdf;
            }

            $pricePerPage =
                $validated['print_mode'] === 'color'
                    ? $printJob->color_price_per_page
                    : $printJob->black_price_per_page;

            $previewPdf = $previewGenerator->generate(
                sourcePath: $pdfForPreview,
                printMode: $validated['print_mode'],
                orientation: $validated['orientation'],
                paperSize: $validated['paper_size']
            );
        } catch (Throwable $exception) {
            logger()->error('Preview generation failed', [
                'print_job_id' => $printJob->id,
                'message' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            return back()->withErrors([
                'document' => $exception->getMessage(),
            ]);
        }

        $printJob->update([
            'pages' => $pages,
            'page_selection' => $selection,
            'selected_pages_count' => count($selectedPages),
            'converted_pdf_path' =>
                'print-jobs/converted/' .
                basename($workingPdf),
            'filtered_pdf_path' => $relativeFilteredPath,
            'preview_pdf_path' =>
                'print-jobs/previews/' .
                basename($previewPdf),
            'print_mode' => $validated['print_mode'],
            'orientation' => $validated['orientation'],
            'paper_size' => $validated['paper_size'],
            'price_per_page' => $pricePerPage,
            'total_amount' =>
                count($selectedPages) *
                $pricePerPage,
        ]);

        $this->refreshExpiration($printJob);

        return back();
    }

    public function cancel(
        PrintJob $printJob,
        KioskSessionLock $kioskSessionLock,
        KioskCreditService $creditService
    ): RedirectResponse {
        if ($printJob->status === 'pending_payment') {
            $creditService->refundFrom($printJob);
        }

        $printJob->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        $kioskSessionLock->unlock();

        return redirect()->route('kiosk.home');
    }

    public function connect(): View
    {
        return view('kiosk.connect', [
            'wifiSsid' => 'PisoPrint',
            'wifiPassword' => '12345678',
            'uploadUrl' => url('/upload'),
            'wifiQr' => 'WIFI:T:WPA;S:PisoPrint;P:12345678;;',
        ]);
    }

    public function transfer(): View
    {
        return view('kiosk.transfer', [
            'uploadUrl' => url('/mobile-upload'),
        ]);
    }

    public function mobileUpload(): View
    {
        return view('kiosk.mobile-upload');
    }

    public function mobileStore(
        Request $request,
        PrintJobFactory $printJobFactory
    ): RedirectResponse|View {
        $validated = $request->validate(
            $this->uploadValidationRules(),
            $this->uploadValidationMessages()
        );

        try {
            $printJob = $printJobFactory->createFromUploadedFile(
                $validated['document']
            );
        } catch (Throwable $exception) {
            logger()->error('Mobile upload failed', [
                'message' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            return back()->withErrors([
                'document' => $exception->getMessage(),
            ]);
        }

        $printJob->update([
            'status' => 'uploaded',
        ]);

        return view('kiosk.mobile-upload-success');
    }

    private function buildLayoutAwarePdf(
        PrintJob $printJob,
        string $orientation,
        string $paperSize,
        FileConverter $fileConverter
    ): string {
        $extension = strtolower(
            $printJob->original_extension ?? ''
        );

        $editableExtensions = [
            'doc',
            'docx',
            'txt',
            'rtf',
            'odt',
        ];

        if (
            in_array(
                $extension,
                $editableExtensions,
                true
            )
        ) {
            $originalPath = Storage::disk('local')
                ->path($printJob->original_file_path);

            return $fileConverter->convertToPdf(
                path: $originalPath,
                orientation: $orientation,
                paperSize: $paperSize
            );
        }

        return Storage::disk('local')
            ->path($printJob->converted_pdf_path);
    }

    private function refreshExpiration(PrintJob $printJob): void
    {
        $printJob->update([
            'expires_at' => now()->addMinutes(5),
        ]);
    }

    private function uploadValidationRules(): array
    {
        return [
            'document' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,txt',
                'max:102400',
            ],
        ];
    }

    private function uploadValidationMessages(): array
    {
        return [
            'document.required' => 'Please select a file.',
            'document.file' => 'The selected document is not a valid file.',
            'document.mimes' =>
                'Unsupported file type. Please upload PDF, Word, PowerPoint, Excel, JPG, PNG, or TXT.',
            'document.max' =>
                'File is too large. Maximum upload size is 100MB.',
            'document.uploaded' =>
                'The document failed to upload. Please try a smaller file or use JPG/PNG/PDF.',
        ];
    }
}
