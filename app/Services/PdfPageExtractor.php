<?php

namespace App\Services;

use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class PdfPageExtractor
{
    public function extract(
        string $sourcePath,
        string $pageSelection
    ): string {
        if ($pageSelection === 'all') {
            return $sourcePath;
        }

        $outputPath =
            storage_path('app/print-jobs/filtered/') .
            Str::uuid() .
            '.pdf';

        if (! is_dir(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0777, true);
        }

        $process = new Process([
            'qpdf',
            $sourcePath,
            '--pages',
            $sourcePath,
            $pageSelection,
            '--',
            $outputPath,
        ]);

        $process->setTimeout(300);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new \RuntimeException(
                $process->getErrorOutput()
            );
        }

        return $outputPath;
    }
}
