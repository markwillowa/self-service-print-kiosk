<?php

namespace App\Console\Commands;

use App\Models\PrintJob;
use App\Services\PrinterService;
use App\Services\PrintJobStateService;
use Illuminate\Console\Command;
use RuntimeException;

class ProcessPrintQueue extends Command
{
    protected $signature = 'print:process-queue';

    protected $description = 'Process queued print jobs';

    public function handle(
        PrinterService $printerService,
        PrintJobStateService $stateService
    ): void
    {
        $jobs = PrintJob::query()
            ->where('status', 'queued')
            ->get();

        foreach ($jobs as $job) {
            try {
                $stateService->transition(
                    $job,
                    'printing'
                );
            } catch (RuntimeException) {
                continue;
            }

            $success = $printerService->print($job);

            if (! $success) {
                try {
                    $stateService->transition(
                        $job,
                        'failed'
                    );
                } catch (RuntimeException) {
                }
            }
        }
    }
}
