<?php

namespace App\Http\Controllers;

use App\Models\PrintJob;
use App\Services\PdfPageCounter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KioskController extends Controller
{
    public function home(): View
    {
        return view('kiosk.home');
    }

    public function upload(): View
    {
        return view('kiosk.upload');
    }

    public function store(
        Request $request,
        PdfPageCounter $pdfPageCounter
    ): RedirectResponse {
        $validated = $request->validate([
            'document' => ['required', 'file', 'mimes:pdf', 'max:110000'],
        ]);

        $file = $validated['document'];

        $path = $file->store('print-jobs', 'local');

        $fullPath = Storage::disk('local')->path($path);

        $pages = $pdfPageCounter->count($fullPath);
        $pricePerPage = 1;

        $printJob = PrintJob::create([
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'pages' => $pages,
            'price_per_page' => $pricePerPage,
            'total_amount' => $pages * $pricePerPage,
            'paid_amount' => 0,
            'status' => 'pending_payment',
        ]);

        return redirect()->route('kiosk.payment', $printJob);
    }

    public function payment(PrintJob $printJob): View
    {
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

        return redirect()->route('kiosk.payment', $printJob);
    }

    public function print(PrintJob $printJob): RedirectResponse
    {
        if ($printJob->status !== 'paid') {
            return redirect()->route('kiosk.payment', $printJob);
        }

        $printJob->update([
            'status' => 'printing',
        ]);

        return redirect()->route('kiosk.printing', $printJob);
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

        $printJob->refresh();

        if ($printJob->paid_amount >= $printJob->total_amount) {
            $printJob->update([
                'status' => 'paid',
            ]);
        }

        return redirect()->route('kiosk.payment', $printJob);
    }

    public function printing(PrintJob $printJob): View
    {
        return view('kiosk.printing', [
            'printJob' => $printJob,
        ]);
    }
}
