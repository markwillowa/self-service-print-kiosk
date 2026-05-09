<?php

namespace App\Console\Commands;

use App\Models\PrintJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupPrintJobs extends Command
{
    protected $signature = 'print:cleanup';

    protected $description = 'Delete old completed or failed print job files';

    public function handle(): int
    {
        $jobs = PrintJob::query()
            ->whereIn('status', ['completed', 'failed', 'cancelled'])
            ->where('updated_at', '<=', now()->subMinutes(10))
            ->get();

        foreach ($jobs as $job) {
            Storage::disk('local')->delete($job->file_path);

            $job->delete();
        }

        return self::SUCCESS;
    }
}
