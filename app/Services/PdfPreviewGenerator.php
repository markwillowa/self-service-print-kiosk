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
        string $paperSize,
        string $margin = 'normal'
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
            Str::uuid() .
            '.pdf';

        $marginPoints = match ($margin) {
            'narrow' => 9.0,
            'wide' => 36.0,
            'none', 'no_margin', 'fit', 'fit_to_screen' => 0.0,
            default => 18.0,
        };

        [$wPoints, $hPoints] = match ($paperSize) {
            'long' => [612.0, 1008.0],
            'a4' => [595.28, 841.89],
            default => [612.0, 792.0],
        };

        if ($orientation === 'landscape') {
            [$wPoints, $hPoints] = [$hPoints, $wPoints];
        }

        $command = [
            'gs',
            '-sDEVICE=pdfwrite',
            '-dCompatibilityLevel=1.4',
            '-dNOPAUSE',
            '-dQUIET',
            '-dBATCH',
            '-dDEVICEWIDTHPOINTS=' . (int) round($wPoints),
            '-dDEVICEHEIGHTPOINTS=' . (int) round($hPoints),
            '-dFIXEDMEDIA',
            '-dAutoRotatePages=/None',
            '-sOutputFile=' . $outputPath,
        ];

        if ($marginPoints <= 0) {
            $command[] = '-dPDFFitPage';
        }

        if ($printMode === 'black') {
            $command[] = '-sColorConversionStrategy=Gray';
            $command[] = '-dProcessColorModel=/DeviceGray';
        }

        $postscript = [
            "/PageSize [{$wPoints} {$hPoints}]",
        ];

        if ($marginPoints > 0) {
            $marginDouble = $marginPoints * 2;
            $postscript[] = "/Install { clippath pathbbox pop pop /H exch def /W exch def {$marginPoints} {$marginPoints} translate W {$marginDouble} sub W div H {$marginDouble} sub H div scale }";
        }

        $command[] = '-c';
        $command[] = '<<' . implode(' ', $postscript) . '>> setpagedevice';

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
