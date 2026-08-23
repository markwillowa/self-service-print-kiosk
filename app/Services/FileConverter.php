<?php

namespace App\Services;

use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;
use Symfony\Component\Process\Process;

class FileConverter
{
    public function convertToPdf(
        string $path,
        string $orientation = 'portrait',
        string $paperSize = 'short',
        string $margin = 'normal'
    ): string {
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

        if ($extension === 'docx') {
            $path = $this->prepareDocxLayout(
                path: $path,
                orientation: $orientation,
                paperSize: $paperSize,
                margin: $margin
            );
        }

        if ($extension === 'txt') {
            $path = $this->prepareTxtLayout(
                path: $path,
                orientation: $orientation,
                paperSize: $paperSize,
                margin: $margin
            );
        }

        return $this->convertUsingLibreOffice(
            path: $path,
            outputDirectory: $outputDirectory
        );
    }

    private function convertUsingLibreOffice(
        string $path,
        string $outputDirectory
    ): string {
        $outputPath =
            $outputDirectory .
            '/' .
            Str::uuid() .
            '.pdf';

        $profilePath = storage_path(
            'app/libreoffice-profile-' . Str::uuid()
        );

        if (! is_dir($profilePath)) {
            mkdir($profilePath, 0777, true);
        }

        $sofficePath = config('services.libreoffice.path', 'soffice');

        $process = new Process([
            $sofficePath,
            '--headless',
            '--norestore',
            '--nodefault',
            '--nofirststartwizard',
            '-env:UserInstallation=file://' . $profilePath,
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
                trim(
                    $process->getErrorOutput() ?:
                        $process->getOutput()
                )
            );
        }

        $generatedPath =
            $outputDirectory .
            '/' .
            pathinfo($path, PATHINFO_FILENAME) .
            '.pdf';

        clearstatcache();

        if (! file_exists($generatedPath)) {
            throw new RuntimeException(
                'Converted PDF file not found.'
            );
        }

        rename($generatedPath, $outputPath);

        return $outputPath;
    }

    private function prepareDocxLayout(
        string $path,
        string $orientation,
        string $paperSize,
        string $margin = 'normal'
    ): string {
        $tempDirectory = storage_path(
            'app/docx-layout-' . Str::uuid()
        );

        mkdir($tempDirectory, 0777, true);

        $sourceDocx = $tempDirectory . '/source.docx';

        copy($path, $sourceDocx);

        $zip = new ZipArchive();

        if ($zip->open($sourceDocx) !== true) {
            throw new RuntimeException(
                'Unable to open DOCX file.'
            );
        }

        $documentXml = $zip->getFromName('word/document.xml');

        if ($documentXml === false) {
            $zip->close();

            throw new RuntimeException(
                'Invalid DOCX document structure.'
            );
        }

        [$width, $height] = $this->pageSizeTwips(
            orientation: $orientation,
            paperSize: $paperSize
        );

        $marginTwips = $this->marginTwips($margin);

        $pgSzXml = '<w:pgSz w:w="' .
            $width .
            '" w:h="' .
            $height .
            '"' .
            ($orientation === 'landscape' ? ' w:orient="landscape"' : '') .
            '/>';

        $pgMarXml = '<w:pgMar w:top="' .
            $marginTwips .
            '" w:right="' .
            $marginTwips .
            '" w:bottom="' .
            $marginTwips .
            '" w:left="' .
            $marginTwips .
            '" w:header="0" w:footer="0" w:gutter="0"/>';

        if (str_contains($documentXml, '<w:pgSz')) {
            $documentXml = preg_replace(
                '/<w:pgSz[^>]*\/>/',
                $pgSzXml,
                $documentXml
            );
        } elseif (str_contains($documentXml, '</w:sectPr>')) {
            $documentXml = str_replace(
                '</w:sectPr>',
                $pgSzXml . '</w:sectPr>',
                $documentXml
            );
        }

        if (str_contains($documentXml, '<w:pgMar')) {
            $documentXml = preg_replace(
                '/<w:pgMar[^>]*\/>/',
                $pgMarXml,
                $documentXml
            );
        } elseif (str_contains($documentXml, '</w:sectPr>')) {
            $documentXml = str_replace(
                '</w:sectPr>',
                $pgMarXml . '</w:sectPr>',
                $documentXml
            );
        }

        $zip->addFromString(
            'word/document.xml',
            $documentXml
        );

        $zip->close();

        return $sourceDocx;
    }

    private function prepareTxtLayout(
        string $path,
        string $orientation,
        string $paperSize,
        string $margin = 'normal'
    ): string {
        $content = file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException(
                'Unable to read TXT file.'
            );
        }

        $paper = match ($paperSize) {
            'long' => 'legal',
            'a4' => 'a4',
            default => 'letter',
        };

        $marginInches = match ($margin) {
            'narrow' => '0.125in',
            'wide' => '0.50in',
            'none', 'no_margin', 'fit', 'fit_to_screen' => '0in',
            default => '0.25in',
        };

        $fitStyles = ($margin === 'fit' || $margin === 'fit_to_screen')
            ? 'width: 100vw; min-height: 100vh; margin: 0; padding: 0; box-sizing: border-box;'
            : 'margin: ' . $marginInches . ';';

        $htmlPath = storage_path(
            'app/txt-layout-' . Str::uuid() . '.html'
        );

        $html =
            '<!DOCTYPE html>' .
            '<html>' .
            '<head>' .
            '<meta charset="UTF-8">' .
            '<style>' .
            '@page { size: ' .
            $paper .
            ' ' .
            $orientation .
            '; margin: ' .
            $marginInches .
            '; }' .
            'body { font-family: Arial, sans-serif; font-size: 12pt; white-space: pre-wrap; ' .
            $fitStyles .
            ' }' .
            '</style>' .
            '</head>' .
            '<body>' .
            htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') .
            '</body>' .
            '</html>';

        file_put_contents($htmlPath, $html);

        return $htmlPath;
    }

    public function pageSizeTwips(
        string $orientation,
        string $paperSize
    ): array {
        if ($paperSize === 'a4') {
            $width = 11906;
            $height = 16838;
        } elseif ($paperSize === 'long') {
            $width = 12240;
            $height = 20160;
        } else {
            $width = 12240;
            $height = 15840;
        }

        if ($orientation === 'landscape') {
            return [$height, $width];
        }

        return [$width, $height];
    }

    public function marginTwips(string $margin): int
    {
        return match ($margin) {
            'narrow' => 180,
            'wide' => 720,
            'none', 'no_margin', 'fit', 'fit_to_screen' => 0,
            default => 360,
        };
    }
}
