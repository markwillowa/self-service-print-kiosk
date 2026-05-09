<?php

namespace App\Services;

use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class FileConverter
{
    public function convertToPdf(string $path): string
    {
        $extension = strtolower(
            pathinfo($path, PATHINFO_EXTENSION)
        );

        if ($extension === 'pdf') {
            return $path;
        }

        $outputDirectory = storage_path(
            'app/print-jobs/converted'
        );

        $process = new Process([
            'soffice',
            '--headless',
            '--convert-to',
            'pdf',
            '--outdir',
            $outputDirectory,
            $path,
        ]);

        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \RuntimeException(
                $process->getErrorOutput()
            );
        }

        $filenameWithoutExtension = Str::beforeLast(
            basename($path),
            '.'
        );

        return $outputDirectory .
            '/' .
            $filenameWithoutExtension .
            '.pdf';
    }
}
