<?php

namespace App\Services;

use App\Models\PrintJob;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class PrinterService
{
    public function __construct(
        private KioskSessionLock $kioskSessionLock,
        private PrintJobStateService $stateService
    ) {
    }

    public function print(PrintJob $printJob): bool
    {
        try {
            $mode = config('services.printer.mode');

            if ($mode === 'dummy') {
                sleep(2);

                try {
                    $this->stateService->transition(
                        $printJob,
                        'completed'
                    );
                } catch (RuntimeException) {
                    return false;
                }

                $this->kioskSessionLock->unlock();

                return true;
            }

            if ($mode === 'cups') {
                return $this->printViaCups($printJob);
            }

            return false;
        } catch (Throwable) {
            return false;
        }
    }

    private function printViaCups(
        PrintJob $printJob
    ): bool {
        $path = Storage::disk('local')->path(
            $printJob->filtered_pdf_path
                ?: $printJob->converted_pdf_path
        );

        $printerName = config('services.printer.name');

        $command = [
            'lp',
        ];

        if ($printerName) {
            $command[] = '-d';
            $command[] = $printerName;
        }

        if (
            $printJob->page_selection &&
            $printJob->page_selection !== 'all'
        ) {
            $command[] = '-P';
            $command[] = $printJob->page_selection;
        }

        if ($printJob->print_mode === 'black') {
            $command[] = '-o';
            $command[] = 'ColorModel=Gray';
        }

        $command[] = $path;

        $process = new Process($command);

        $process->setTimeout(300);

        $process->run();

        if (! $process->isSuccessful()) {
            return false;
        }

        try {
            $this->stateService->transition(
                $printJob,
                'completed'
            );
        } catch (RuntimeException) {
            return false;
        }

        $this->kioskSessionLock->unlock();

        return true;
    }
}
