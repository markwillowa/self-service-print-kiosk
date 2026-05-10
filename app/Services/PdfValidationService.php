<?php

namespace App\Services;

use RuntimeException;
use Symfony\Component\Process\Process;

class PdfValidationService
{
    private const MAX_PAGES = 500;

    public function validate(string $path): void
    {
        $process = new Process([
            'pdfinfo',
            $path,
        ]);

        $process->setTimeout(60);

        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                'Invalid PDF document.'
            );
        }

        preg_match(
            '/^Pages:\s+(\d+)/m',
            $process->getOutput(),
            $matches
        );

        $pages = isset($matches[1])
            ? (int) $matches[1]
            : 0;

        if ($pages <= 0) {
            throw new RuntimeException(
                'Unable to determine PDF pages.'
            );
        }

        if ($pages > self::MAX_PAGES) {
            throw new RuntimeException(
                'PDF exceeds maximum page limit.'
            );
        }
    }
}
