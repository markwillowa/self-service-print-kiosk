<?php

namespace App\Jobs;

use App\Models\PrintJob;
use App\Services\PrinterService;
use App\Services\PrintJobStateService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use RuntimeException;
use Throwable;

class ProcessPrintJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PrintJob $printJob
    ) {
    }

    public function handle(
        PrinterService $printerService,
        PrintJobStateService $stateService
    ): void {
        try {
            $stateService->transition(
                $this->printJob,
                'printing'
            );

            $success = $printerService->print(
                $this->printJob->fresh()
            );

            if (! $success) {
                $stateService->transition(
                    $this->printJob,
                    'failed'
                );
            }
        } catch (Throwable) {
            try {
                $stateService->transition(
                    $this->printJob,
                    'failed'
                );
            } catch (RuntimeException) {
            }
        }
    }
}
