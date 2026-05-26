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
        } catch (Throwable $exception) {
            logger()->error('Failed to print job', [
                'print_job_id' => $printJob->id,
                'exception' => $exception,
            ]);

            return false;
        }
    }

    private function printViaCups(
        PrintJob $printJob
    ): bool {
        $printJob->refresh();

        $relativePath =
            $printJob->preview_pdf_path
                ?: $printJob->filtered_pdf_path
                ?: $printJob->converted_pdf_path;

        $path = Storage::disk('local')
            ->path($relativePath);

        if (! file_exists($path)) {
            logger()->error('Print file not found', [
                'path' => $path,
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
        $command[] = 'print-quality=5';

        $command[] = '-o';
        $command[] = 'Resolution=600dpi';

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
            ]);

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
