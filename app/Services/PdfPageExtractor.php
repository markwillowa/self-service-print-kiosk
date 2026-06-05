<?php

namespace App\Services;

use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class PdfPageExtractor
{
    public function __construct(
        private PageSelectionParser $pageSelectionParser
    ) {
    }

    public function extract(
        string $sourcePath,
        string $pageSelection
    ): string {
        $pageSelection = $this->pageSelectionParser
            ->normalize($pageSelection);

        if ($pageSelection === 'all') {
            return $sourcePath;
        }

        $outputDirectory = storage_path(
            'app/print-jobs/filtered'
        );

        if (! is_dir($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }

        $outputPath =
            $outputDirectory .
            '/' .
            Str::uuid() .
            '.pdf';

        $process = new Process([
            'qpdf',
            '--warning-exit-0',
            '--empty',
            '--pages',
            $sourcePath,
            $pageSelection,
            '--',
            $outputPath,
        ]);

        $process->setTimeout(300);
        $process->run();

        if (! file_exists($outputPath)) {
            throw new RuntimeException(
                trim(
                    $process->getErrorOutput() ?:
                        $process->getOutput()
                ) ?: 'Failed to extract selected PDF pages.'
            );
        }

        return $outputPath;
    }
}
