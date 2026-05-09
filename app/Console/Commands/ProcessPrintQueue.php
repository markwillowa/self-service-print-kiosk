<?php

namespace App\Console\Commands;

use App\Models\PrintJob;
use App\Services\PrinterService;
use Illuminate\Console\Command;

class ProcessPrintQueue extends Command
{
    protected $signature = 'print:process-queue';

    protected $description = 'Process queued print jobs';

    public function handle(PrinterService $printerService): void
    {
        $jobs = PrintJob::query()
            ->where('status', 'queued')
            ->get();

        foreach ($jobs as $job) {
            $job->update([
                'status' => 'printing',
            ]);

            $success = $printerService->print($job);

            if (! $success) {
                $job->update([
                    'status' => 'failed',
                ]);
            }
        }
    }
}
