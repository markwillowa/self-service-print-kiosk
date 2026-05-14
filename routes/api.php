<?php

use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/coin', function (Request $request) {
    $request->validate([
        'amount' => ['required', 'integer', 'min:1'],
    ]);

    $printJob = PrintJob::query()
        ->where('status', 'pending_payment')
        ->latest()
        ->first();

    if (! $printJob) {
        return response()->json([
            'success' => false,
            'message' => 'No active payment session.',
        ]);
    }

    $printJob->increment(
        'paid_amount',
        $request->integer('amount')
    );

    $printJob->refresh();

    if (
        $printJob->paid_amount >=
        $printJob->total_amount
    ) {
        $printJob->update([
            'status' => 'paid',
        ]);
    }

    return response()->json([
        'success' => true,
        'paid_amount' => $printJob->paid_amount,
    ]);
});
