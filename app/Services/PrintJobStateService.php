<?php

namespace App\Services;

use App\Models\PrintJob;
use RuntimeException;

class PrintJobStateService
{
    private const ALLOWED_TRANSITIONS = [
        'pending_payment' => [
            'paid',
            'cancelled',
        ],

        'paid' => [
            'queued',
            'cancelled',
        ],

        'queued' => [
            'printing',
            'failed',
        ],

        'printing' => [
            'completed',
            'failed',
        ],

        'completed' => [],

        'failed' => [],

        'cancelled' => [],
    ];

    public function transition(
        PrintJob $printJob,
        string $newStatus
    ): void {
        $currentStatus = $printJob->status;

        $allowed = self::ALLOWED_TRANSITIONS[
        $currentStatus
        ] ?? [];

        if (! in_array($newStatus, $allowed, true)) {
            throw new RuntimeException(
                sprintf(
                    'Invalid status transition from [%s] to [%s].',
                    $currentStatus,
                    $newStatus
                )
            );
        }

        $printJob->update([
            'status' => $newStatus,
        ]);
    }
}
