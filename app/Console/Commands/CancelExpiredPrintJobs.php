<?php

namespace App\Console\Commands;

use App\Models\PrintJob;
use Illuminate\Console\Command;

class CancelExpiredPrintJobs extends Command
{
    protected $signature = 'print:cancel-expired';

    protected $description = 'Cancel expired print jobs';

    public function handle(): int
    {
        $jobs = PrintJob::query()
            ->whereIn('status', [
                'uploaded',
                'pending_payment',
                'paid',
            ])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($jobs as $job) {
            $job->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'expires_at' => null,
            ]);

            $this->info(
                'Cancelled expired job: ' .
                $job->original_filename
            );
        }

        return self::SUCCESS;
    }
}
