<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPrintJob;
use App\Models\Company;
use App\Models\CreditTransaction;
use App\Models\PrintJob;
use App\Models\Voucher;
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

            if (! $printJob) {
                $kioskSessionLock->unlock();

                return view('kiosk.home');
            }

            if (in_array($printJob->status, [
                'queued',
                'printing',
            ], true)) {
                return redirect()->route('kiosk.status', $printJob);
            }

            if ($printJob->status === 'completed') {
                $kioskSessionLock->unlock();

                return view('kiosk.home');
            }

            if (in_array($printJob->status, [
                'uploaded',
                'pending_payment',
                'paid',
            ], true)) {
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
        KioskSessionLock $kioskSessionLock,
        PrintJobFactory $printJobFactory
    ): RedirectResponse {
        if ($printJob->status !== 'uploaded') {
            return redirect()->route('kiosk.upload');
        }

        try {
            $printJob = $printJobFactory->prepareUploadedJob(
                $printJob
            );
        } catch (Throwable $exception) {
            logger()->error('Uploaded file preparation failed', [
                'print_job_id' => $printJob->id,
                'message' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            return redirect()
                ->route('kiosk.upload')
                ->withErrors([
                    'document' => $exception->getMessage(),
                ]);
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

    public function paperCheck(PrintJob $printJob): RedirectResponse|View
    {
        $printJob->refresh();

        if ($printJob->status === 'cancelled') {
            return redirect()->route('kiosk.home');
        }

        if ($printJob->status !== 'paid') {
            return redirect()->route('kiosk.payment', $printJob);
        }

        if (
            $printJob->expires_at &&
            now()->greaterThan($printJob->expires_at)
        ) {
            return redirect()->route('kiosk.home');
        }

        $this->refreshExpiration($printJob);

        return view('kiosk.paper-check', [
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
        $printJob->refresh();

        abort_if(
            $printJob->expires_at &&
            now()->greaterThan($printJob->expires_at),
            403
        );

        abort_if($printJob->status !== 'paid', 403);

        abort_if(
            $printJob->paid_amount < $printJob->total_amount,
            403
        );

        try {
            $stateService->transition($printJob, 'queued');

            $printJob->update([
                'expires_at' => null,
            ]);

            ProcessPrintJob::dispatchSync($printJob);
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
        $printJob->refresh();

        if ($printJob->status === 'cancelled') {
            return redirect()->route('kiosk.home');
        }

        if (
            ! in_array($printJob->status, [
                'queued',
                'printing',
                'completed',
            ], true) &&
            $printJob->expires_at &&
            now()->greaterThan($printJob->expires_at)
        ) {
            return redirect()->route('kiosk.home');
        }

        if (! in_array($printJob->status, [
            'queued',
            'printing',
            'completed',
        ], true)) {
            $this->refreshExpiration($printJob);
        }

        return view('kiosk.status', [
            'printJob' => $printJob,
        ]);
    }

    public function preview(PrintJob $printJob): RedirectResponse|View
    {
        $printJob->refresh();

        if ($printJob->status === 'cancelled') {
            return redirect()->route('kiosk.home');
        }

        if (in_array($printJob->status, [
            'queued',
            'printing',
            'completed',
        ], true)) {
            return redirect()->route('kiosk.status', $printJob);
        }

        if (
            $printJob->expires_at &&
            now()->greaterThan($printJob->expires_at)
        ) {
            return redirect()->route('kiosk.home');
        }

        $this->refreshExpiration($printJob);

        $printJob->refresh();

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
            'copies' => ['required', 'integer', 'min:1', 'max:99'],
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

        $copies = (int) $validated['copies'];

        $selectedPagesCount = count($selectedPages);

        $totalAmount =
            $selectedPagesCount *
            $copies *
            $pricePerPage;

        $printJob->update([
            'pages' => $pages,

            'page_selection' => $selection,

            'selected_pages_count' => $selectedPagesCount,

            'copies' => $copies,

            'converted_pdf_path' => $this->relativePdfPathFor(
                printJob: $printJob,
                workingPdf: $workingPdf
            ),

            'filtered_pdf_path' => $relativeFilteredPath,

            'preview_pdf_path' =>
                'print-jobs/previews/' .
                basename($previewPdf),

            'print_mode' => $validated['print_mode'],

            'orientation' => $validated['orientation'],

            'paper_size' => $validated['paper_size'],

            'price_per_page' => $pricePerPage,

            'total_amount' => $totalAmount,
        ]);

        $this->refreshExpiration($printJob);

        return back();
    }

    private function relativePdfPathFor(
        PrintJob $printJob,
        string $workingPdf
    ): string {
        if ($printJob->original_extension === 'pdf') {
            return $printJob->original_file_path;
        }

        $convertedDirectory = storage_path('app/print-jobs/converted');

        if (str_starts_with($workingPdf, $convertedDirectory)) {
            return 'print-jobs/converted/' . basename($workingPdf);
        }

        return $printJob->converted_pdf_path;
    }

    public function cancel(
        PrintJob $printJob,
        KioskSessionLock $kioskSessionLock,
        KioskCreditService $creditService
    ): RedirectResponse {
        $printJob->refresh();

        if (in_array($printJob->status, [
            'queued',
            'printing',
            'completed',
        ], true)) {
            return redirect()->route('kiosk.status', $printJob);
        }

        if (! in_array($printJob->status, [
            'uploaded',
            'pending_payment',
            'paid',
        ], true)) {
            return redirect()->route('kiosk.home');
        }

        if ($printJob->status === 'pending_payment') {
            $creditService->refundFrom($printJob);
        }

        $printJob->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'expires_at' => null,
        ]);

        $kioskSessionLock->unlock();

        return redirect()->route('kiosk.home');
    }

    public function connect(): View
    {
        $company = Company::query()
            ->latest()
            ->first();

        $kioskName =
            $company?->kiosk_name
            ?? 'Piso Print';

        $wifiSsid = match ($kioskName) {
            'Self-Service Print' => 'SelfServicePrint',
            default => 'PisoPrint',
        };

        $baseUrl = config('app.url');

        return view('kiosk.connect', [
            'wifiSsid' => $wifiSsid,

            'wifiPassword' => '12345678',

            'uploadUrl' => $baseUrl . '/mobile-upload',

            'wifiQr' =>
                'WIFI:T:WPA;S:' .
                $wifiSsid .
                ';P:12345678;;',
        ]);
    }

    public function transfer(): View
    {
        return view('kiosk.transfer', [
            'uploadUrl' => config('app.url') . '/mobile-upload',
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
            $printJobFactory->createUploadedOnly(
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

        if ($extension === 'pdf') {
            return Storage::disk('local')
                ->path($printJob->original_file_path);
        }

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

    public function backFromPreview(
        PrintJob $printJob,
        KioskSessionLock $kioskSessionLock
    ): RedirectResponse {
        if ($printJob->status === 'pending_payment') {
            $printJob->update([
                'status' => 'uploaded',
                'paid_amount' => 0,
                'expires_at' => null,
            ]);
        }

        $kioskSessionLock->unlock();

        return redirect()->route('kiosk.upload');
    }

    public function redeemVoucher(
        Request $request,
        KioskCreditService $creditService
    ): RedirectResponse {
        $validated = $request->validate([
            'voucher_code' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        $code = strtoupper(
            trim($validated['voucher_code'])
        );

        $voucher = Voucher::query()
            ->where('code', $code)
            ->where('is_used', false)
            ->first();

        if (! $voucher) {
            return back()->withErrors([
                'voucher_code' => 'Invalid or already used voucher.',
            ]);
        }

        $creditService->add((int) $voucher->amount);

        $voucher->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        return back()->with([
            'voucher_success' => 'Voucher redeemed successfully.',
        ]);
    }
}
