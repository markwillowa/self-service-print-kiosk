<?php

namespace App\Services;

use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class PdfPreviewGenerator
{
    public function generate(
        string $sourcePath,
        string $printMode,
        string $orientation,
        string $paperSize
    ): string {
        $outputDirectory = storage_path(
            'app/print-jobs/previews'
        );

        if (! is_dir($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }

        $outputPath =
            $outputDirectory .
            '/' .
            \Illuminate\Support\Str::uuid() .
            '.pdf';

        $paper = match ($paperSize) {
            'long' => 'legal',
            default => 'letter',
        };

        $command = [
            'gs',

            '-sDEVICE=pdfwrite',

            '-dCompatibilityLevel=1.4',

            '-dNOPAUSE',

            '-dQUIET',

            '-dBATCH',

            '-sPAPERSIZE=' . $paper,

            '-sOutputFile=' . $outputPath,
        ];

        /*
        |--------------------------------------------------------------------------
        | Black / Grayscale
        |--------------------------------------------------------------------------
        */

        if ($printMode === 'black') {
            $command[] = '-sColorConversionStrategy=Gray';

            $command[] = '-dProcessColorModel=/DeviceGray';
        }

        /*
        |--------------------------------------------------------------------------
        | Landscape
        |--------------------------------------------------------------------------
        */

        if ($orientation === 'landscape') {
            $command[] = '-c';

            $command[] =
                '<</Orientation 3>> setpagedevice';
        }

        /*
        |--------------------------------------------------------------------------
        | Input PDF
        |--------------------------------------------------------------------------
        */

        $command[] = '-f';

        $command[] = $sourcePath;

        $process = new Process($command);

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
