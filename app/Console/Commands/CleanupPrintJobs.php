<?php

namespace App\Console\Commands;

use App\Models\PrintJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupPrintJobs extends Command
{
    protected $signature = 'print:cleanup';

    protected $description =
        'Delete old print job files but keep job history';

    public function handle(): int
    {
        $jobs = PrintJob::query()
            ->whereIn('status', [
                'completed',
                'cancelled',
                'failed',
            ])
            ->where('updated_at', '<=', now()->subHour())
            ->get();

        foreach ($jobs as $job) {
            $paths = [
                $job->original_file_path,
                $job->converted_pdf_path,
                $job->filtered_pdf_path,
                $job->preview_pdf_path,
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
                'Cleaned files for print job: ' .
                $job->original_filename
            );
        }

        return self::SUCCESS;
    }
}
