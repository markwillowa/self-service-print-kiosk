<?php

namespace App\Services;

use RuntimeException;

class PageSelectionParser
{
    public function parse(
        string $input,
        int $maxPages
    ): array {
        $pages = [];

        $parts = explode(
            ',',
            str_replace(' ', '', $input)
        );

        foreach ($parts as $part) {
            if (str_contains($part, '-')) {
                [$start, $end] = explode('-', $part);

                $start = (int) $start;
                $end = (int) $end;

                if (
                    $start > 0 &&
                    $end <= $maxPages &&
                    $start <= $end
                ) {
                    $pages = array_merge(
                        $pages,
                        range($start, $end)
                    );
                }
            } else {
                $page = (int) $part;

                if (
                    $page > 0 &&
                    $page <= $maxPages
                ) {
                    $pages[] = $page;
                }
            }
        }

        $pages = array_unique($pages);

        sort($pages);

        if (count($pages) > 500) {
            throw new RuntimeException(
                'Too many selected pages.'
            );
        }

        return $pages;
    }
}
