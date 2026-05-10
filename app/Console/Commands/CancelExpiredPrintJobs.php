<?php

namespace App\Console\Commands;

use App\Models\PrintJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CancelExpiredPrintJobs extends Command
{
    protected $signature = 'print:cancel-expired';

    protected $description = 'Cancel expired unpaid print jobs';

    public function handle(): int
    {
        $jobs = PrintJob::query()
            ->whereIn('status', [
                'pending_payment',
            ])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($jobs as $job) {
            $job->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            if ($job->original_file_path) {
                Storage::disk('local')
                    ->delete($job->original_file_path);
            }

            if ($job->converted_pdf_path) {
                Storage::disk('local')
                    ->delete($job->converted_pdf_path);
            }

            if ($job->filtered_pdf_path) {
                Storage::disk('local')
                    ->delete($job->filtered_pdf_path);
            }
        }

        return self::SUCCESS;
    }
}
