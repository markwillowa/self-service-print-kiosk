<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPrintJob;
use App\Models\CreditTransaction;
use App\Models\PrintJob;
use App\Services\FileConverter;
use App\Services\FileValidationService;
use App\Services\KioskSessionLock;
use App\Services\PageSelectionParser;
use App\Services\PdfPageCounter;
use App\Services\PdfPageExtractor;
use App\Services\PrintJobFactory;
use App\Services\PrintJobStateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use RuntimeException;

class KioskController extends Controller
{
    public function home(
        KioskSessionLock $kioskSessionLock
    ): RedirectResponse|View
    {
        $activeJobUuid = $kioskSessionLock
            ->activeJobUuid();

        if ($activeJobUuid) {
            $printJob = PrintJob::query()
                ->where('uuid', $activeJobUuid)
                ->first();

            if ($printJob) {
                return redirect()->route(
                    'kiosk.preview',
                    $printJob
                );
            }

            $kioskSessionLock->unlock();
        }

        return view('kiosk.home');
    }

    public function upload(): View
    {
        return view('kiosk.upload');
    }

    public function store(
        Request $request,
        PrintJobFactory $printJobFactory,
        KioskSessionLock $kioskSessionLock
    ): RedirectResponse {
        $validated = $request->validate([
            'document' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png,txt',
                'max:102400',
            ],
        ]);

        $printJob = $printJobFactory
            ->createFromUploadedFile(
                $validated['document']
            );

        $kioskSessionLock->lock($printJob);

        return redirect()->route(
            'kiosk.preview',
            $printJob
        );
    }

    public function payment(PrintJob $printJob): RedirectResponse|View
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

    public function print(
        PrintJob $printJob,
        PrintJobStateService $stateService
    ): RedirectResponse {
        abort_if(
            $printJob->expires_at &&
            now()->greaterThan($printJob->expires_at),
            403
        );

        abort_if(
            $printJob->status !== 'paid',
            403
        );

        try {
            $stateService->transition(
                $printJob,
                'queued'
            );

            ProcessPrintJob::dispatch($printJob);
        } catch (RuntimeException) {
            abort(403);
        }

        return redirect()->route(
            'kiosk.status',
            $printJob
        );
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

    public function printing(PrintJob $printJob): View
    {
        return view('kiosk.printing', [
            'printJob' => $printJob,
        ]);
    }

    public function status(
        PrintJob $printJob
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
        $this->refreshExpiration($printJob);

        return redirect()->route('kiosk.payment', $printJob);
    }

    public function updatePages(
        Request $request,
        PrintJob $printJob,
        PageSelectionParser $parser,
        PdfPageExtractor $extractor
    ): RedirectResponse {
        $validated = $request->validate([
            'page_selection' => ['nullable', 'string'],
        ]);

        $selection = strtolower(
            trim($validated['page_selection'] ?? '')
        );

        if ($selection === '' || $selection === 'all') {
            $selectedPages = range(1, $printJob->pages);

            $selection = 'all';

            $relativeFilteredPath = null;
        } else {
            try {
                $selectedPages = $parser->parse(
                    $selection,
                    $printJob->pages
                );
            } catch (RuntimeException $exception) {
                return back()
                    ->withErrors([
                        'page_selection' => $exception->getMessage(),
                    ]);
            }

            $sourcePdf = Storage::disk('local')
                ->path($printJob->converted_pdf_path);

            $filteredPdf = $extractor->extract(
                $sourcePdf,
                $selection
            );

            $relativeFilteredPath =
                'print-jobs/filtered/' .
                basename($filteredPdf);
        }

        $selectedPagesCount = count($selectedPages);

        abort_if($selectedPagesCount === 0, 422);

        $printJob->update([
            'page_selection' => $selection,

            'selected_pages_count' => $selectedPagesCount,

            'filtered_pdf_path' => $relativeFilteredPath,

            'total_amount' =>
                $selectedPagesCount *
                $printJob->price_per_page,
        ]);

        $this->refreshExpiration($printJob);

        return back();
    }

    public function updatePrintMode(
        Request $request,
        PrintJob $printJob
    ): RedirectResponse {
        $validated = $request->validate([
            'print_mode' => ['required', 'in:black,color'],
        ]);

        $mode = $validated['print_mode'];

        $pricePerPage =
            $mode === 'color'
                ? $printJob->color_price_per_page
                : $printJob->black_price_per_page;

        $printJob->update([
            'print_mode' => $mode,

            'price_per_page' => $pricePerPage,

            'total_amount' =>
                $printJob->selected_pages_count *
                $pricePerPage,
        ]);

        $this->refreshExpiration($printJob);

        return back();
    }

    private function refreshExpiration(PrintJob $printJob): void
    {
        $printJob->update([
            'expires_at' => now()->addMinutes(5),
        ]);
    }

    public function cancel(
        PrintJob $printJob,
        KioskSessionLock $kioskSessionLock
    ): RedirectResponse {
        $printJob->update([
            'status' => 'cancelled',
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

            'wifiQr' =>
                'WIFI:T:WPA;S:PisoPrint;P:12345678;;',
        ]);
    }

    public function transfer(): View
    {
        return view('kiosk.transfer', [
            'uploadUrl' => 'http://192.168.4.1:8000/upload',
        ]);
    }
}
