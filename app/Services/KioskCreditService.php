<?php

namespace App\Services;

use App\Models\PrintJob;
use Illuminate\Support\Facades\Cache;

class KioskCreditService
{
    private const CACHE_KEY = 'kiosk_credit_balance';

    public function balance(): int
    {
        return (int) Cache::get(self::CACHE_KEY, 0);
    }

    public function add(int $amount): void
    {
        Cache::forever(
            self::CACHE_KEY,
            $this->balance() + $amount
        );
    }

    public function useFor(PrintJob $printJob): void
    {
        if ($printJob->status !== 'pending_payment') {
            return;
        }

        $balance = $this->balance();

        if ($balance <= 0) {
            return;
        }

        $remaining = max(
            $printJob->total_amount - $printJob->paid_amount,
            0
        );

        if ($remaining <= 0) {
            return;
        }

        $amountToUse = min($balance, $remaining);

        $printJob->increment('paid_amount', $amountToUse);

        Cache::forever(
            self::CACHE_KEY,
            $balance - $amountToUse
        );

        $printJob->refresh();

        if ($printJob->paid_amount >= $printJob->total_amount) {
            $printJob->update([
                'status' => 'paid',
            ]);
        }
    }

    public function refundFrom(PrintJob $printJob): void
    {
        if ($printJob->paid_amount <= 0) {
            return;
        }

        $this->add((int) $printJob->paid_amount);

        $printJob->update([
            'paid_amount' => 0,
        ]);
    }
}
