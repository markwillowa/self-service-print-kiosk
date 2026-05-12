<?php

namespace App\Console\Commands;

use App\Models\PrintJob;
use Illuminate\Console\Command;

class CancelExpiredPrintJobs extends Command
{
    protected $signature =
        'print:cancel-expired';

    protected $description =
        'Cancel expired print jobs';

    public function handle(): int
    {
        $jobs = PrintJob::query()
            ->whereNotIn('status', [
                'completed',
                'cancelled',
                'failed',
            ])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($jobs as $job) {
            $job->update([
                'status' => 'cancelled',
            ]);

            $this->info(
                'Cancelled expired job: ' .
                $job->original_filename
            );
        }

        return self::SUCCESS;
    }
}
