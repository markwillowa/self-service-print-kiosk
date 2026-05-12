<?php

namespace App\Support;

class FilenameSanitizer
{
    public static function sanitize(
        string $filename
    ): string {
        $filename = preg_replace(
            '/[\\x00-\\x1F\\x7F]/u',
            '',
            $filename
        );

        $filename = trim($filename);

        return mb_substr($filename, 0, 255);
    }
}
