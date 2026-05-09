<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class PdfPageCounter
{
    public function count(string $path): int
    {
        $process = new Process([
            'pdfinfo',
            $path,
        ]);

        $process->setTimeout(30);
        $process->run();

        if (! $process->isSuccessful()) {
            return 1;
        }

        preg_match('/^Pages:\s+(\d+)/m', $process->getOutput(), $matches);

        return isset($matches[1])
            ? (int) $matches[1]
            : 1;
    }
}
