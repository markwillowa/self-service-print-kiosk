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
        string $paperSize = 'short'
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
                paperSize: $paperSize
            );
        }

        if ($extension === 'txt') {
            $path = $this->prepareTxtLayout(
                path: $path,
                orientation: $orientation,
                paperSize: $paperSize
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

        $process = new Process([
            'soffice',
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
        string $paperSize
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

        if (str_contains($documentXml, '<w:pgSz')) {
            $documentXml = preg_replace(
                '/<w:pgSz[^>]*\/>/',
                '<w:pgSz w:w="' .
                $width .
                '" w:h="' .
                $height .
                '"' .
                ($orientation === 'landscape' ? ' w:orient="landscape"' : '') .
                '/>',
                $documentXml
            );
        } else {
            $documentXml = str_replace(
                '</w:sectPr>',
                '<w:pgSz w:w="' .
                $width .
                '" w:h="' .
                $height .
                '"' .
                ($orientation === 'landscape' ? ' w:orient="landscape"' : '') .
                '/></w:sectPr>',
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
        string $paperSize
    ): string {
        $content = file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException(
                'Unable to read TXT file.'
            );
        }

        $paper = $paperSize === 'long'
            ? 'legal'
            : 'letter';

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
            '; margin: 0.5in; }' .
            'body { font-family: Arial, sans-serif; font-size: 12pt; white-space: pre-wrap; }' .
            '</style>' .
            '</head>' .
            '<body>' .
            htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') .
            '</body>' .
            '</html>';

        file_put_contents($htmlPath, $html);

        return $htmlPath;
    }

    private function pageSizeTwips(
        string $orientation,
        string $paperSize
    ): array {
        $width = 12240;

        $height = $paperSize === 'long'
            ? 20160
            : 15840;

        if ($orientation === 'landscape') {
            return [$height, $width];
        }

        return [$width, $height];
    }
}
