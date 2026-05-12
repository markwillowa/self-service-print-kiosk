<?php

namespace App\Console\Commands;

use App\Models\PrintJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupPrintJobs extends Command
{
    protected $signature = 'pisoprint:cleanup';

    protected $description =
        'Delete old print jobs and files';

    public function handle(): int
    {
        $jobs = PrintJob::query()
            ->where(function ($query) {
                $query
                    ->whereIn('status', [
                        'completed',
                        'cancelled',
                        'failed',
                    ])
                    ->where(
                        'updated_at',
                        '<=',
                        now()->subHour()
                    );
            })
            ->orWhere(function ($query) {
                $query
                    ->where('status', 'pending_payment')
                    ->where(
                        'updated_at',
                        '<=',
                        now()->subMinutes(30)
                    );
            })
            ->get();

        foreach ($jobs as $job) {
            $paths = [
                $job->original_file_path,
                $job->converted_pdf_path,
                $job->filtered_pdf_path,
            ];

            foreach ($paths as $path) {
                if (
                    $path &&
                    Storage::disk('local')->exists($path)
                ) {
                    Storage::disk('local')->delete($path);
                }
            }

            $this->info(
                'Deleted print job: ' .
                $job->original_filename
            );

            $job->delete();
        }

        return self::SUCCESS;
    }
}
