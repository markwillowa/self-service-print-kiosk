<?php

namespace App\Services;

use RuntimeException;
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

        if (! is_dir($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }

        $filenameWithoutExtension = pathinfo(
            $path,
            PATHINFO_FILENAME
        );

        $expectedPdfPath =
            $outputDirectory .
            '/' .
            $filenameWithoutExtension .
            '.pdf';

        if (file_exists($expectedPdfPath)) {
            unlink($expectedPdfPath);
        }

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
            throw new RuntimeException(
                $process->getErrorOutput()
            );
        }

        clearstatcache();

        if (! file_exists($expectedPdfPath)) {
            throw new RuntimeException(
                'Converted PDF file not found.'
            );
        }

        return $expectedPdfPath;
    }
}
