<?php

namespace App\Services;

use App\Models\PrintJob;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Throwable;

class PrinterService
{
    public function print(PrintJob $printJob): bool
    {
        try {
            $mode = config('services.printer.mode');

            if ($mode === 'dummy') {
                sleep(2);

                $printJob->update([
                    'status' => 'completed',
                ]);

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

    private function printViaCups(PrintJob $printJob): bool
    {
        $path = Storage::disk('local')->path($printJob->file_path);

        $printerName = config('services.printer.name');

        $command = $printerName
            ? ['lp', '-d', $printerName, $path]
            : ['lp', $path];

        $process = new Process($command);
        $process->setTimeout(120);
        $process->run();

        if (! $process->isSuccessful()) {
            return false;
        }

        $printJob->update([
            'status' => 'completed',
        ]);

        return true;
    }
}
