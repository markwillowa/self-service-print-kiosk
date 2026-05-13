<?php

namespace App\Support;

class FilenameSanitizer
{
    public static function sanitize(
        string $filename
    ): string {
        $filename = iconv(
            'UTF-8',
            'ASCII//TRANSLIT//IGNORE',
            $filename
        );

        $filename = preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            $filename
        );

        return mb_substr($filename, 0, 255);
    }
}
