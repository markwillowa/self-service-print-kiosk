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

                return $this->completePrintJob($printJob);
            }

            if ($mode === 'cups') {
                return $this->printViaCups($printJob);
            }

            return false;
        } catch (Throwable $exception) {
            logger()->error('Failed to print job', [
                'print_job_id' => $printJob->id,
                'exception' => $exception,
            ]);

            return false;
        }
    }

    private function printViaCups(PrintJob $printJob): bool
    {
        $printJob->refresh();

        if ($printJob->status === 'queued') {
            try {
                $this->stateService->transition(
                    $printJob,
                    'printing'
                );

                $printJob->refresh();
            } catch (RuntimeException $exception) {
                logger()->error('Failed to mark print job as printing', [
                    'print_job_id' => $printJob->id,
                    'status' => $printJob->status,
                    'message' => $exception->getMessage(),
                ]);

                return false;
            }
        }

        $relativePath =
            $printJob->preview_pdf_path
                ?: $printJob->filtered_pdf_path
                ?: $printJob->converted_pdf_path;

        $path = Storage::disk('local')
            ->path($relativePath);

        if (! file_exists($path)) {
            logger()->error('Print file not found', [
                'path' => $path,
                'print_job_id' => $printJob->id,
            ]);

            return false;
        }

        $printerName = config('services.printer.name');

        $command = [
            'lp',
        ];

        if ($printerName) {
            $command[] = '-d';
            $command[] = $printerName;
        }

        $copies = max(
            (int) ($printJob->copies ?: 1),
            1
        );

        $command[] = '-n';
        $command[] = (string) $copies;

        if ($printJob->orientation === 'landscape') {
            $command[] = '-o';
            $command[] = 'orientation-requested=4';
        }

        $media =
            $printJob->paper_size === 'long'
                ? 'legal'
                : 'letter';

        $command[] = '-o';
        $command[] = 'media=' . $media;

        if ($printJob->print_mode === 'black') {
            $command[] = '-o';
            $command[] = 'ColorModel=Black';
        } else {
            $command[] = '-o';
            $command[] = 'ColorModel=RGB';
        }

        $command[] = '-o';
        $command[] = 'fit-to-page';

        $command[] = $path;

        logger()->info('Printing via CUPS', [
            'command' => $command,
            'copies' => $copies,
            'print_job_id' => $printJob->id,
        ]);

        $process = new Process($command);
        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            logger()->error('CUPS print failed', [
                'command' => $command,
                'output' => $process->getOutput(),
                'error_output' => $process->getErrorOutput(),
                'print_job_id' => $printJob->id,
            ]);

            try {
                $this->stateService->transition(
                    $printJob,
                    'failed'
                );
            } catch (RuntimeException) {
                // Keep original status if failed transition is not allowed.
            }

            return false;
        }

        return $this->completePrintJob($printJob);
    }

    private function completePrintJob(PrintJob $printJob): bool
    {
        try {
            $printJob->refresh();

            if ($printJob->status === 'queued') {
                $this->stateService->transition(
                    $printJob,
                    'printing'
                );

                $printJob->refresh();
            }

            if ($printJob->status === 'printing') {
                $this->stateService->transition(
                    $printJob,
                    'completed'
                );

                $printJob->refresh();
            }

            if ($printJob->status !== 'completed') {
                logger()->error('Print job could not be completed', [
                    'print_job_id' => $printJob->id,
                    'status' => $printJob->status,
                ]);

                return false;
            }

            $printJob->update([
                'expires_at' => null,
                'completed_at' => now(),
            ]);

            $this->kioskSessionLock->unlock();

            return true;
        } catch (RuntimeException $exception) {
            logger()->error('Failed to complete print job state transition', [
                'print_job_id' => $printJob->id,
                'status' => $printJob->status,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }
}
