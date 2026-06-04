<?php

namespace App\Services;

use RuntimeException;

class PageSelectionParser
{
    public function parse(
        string $input,
        int $maxPages
    ): array {
        $input = $this->normalize($input);

        if ($input === 'all') {
            return range(1, $maxPages);
        }

        if ($input === '') {
            return range(1, $maxPages);
        }

        if (! preg_match('/^\d+(-\d+)?(,\d+(-\d+)?)*$/', $input)) {
            throw new RuntimeException(
                'Invalid page selection format.'
            );
        }

        $pages = [];

        foreach (explode(',', $input) as $part) {
            if (str_contains($part, '-')) {
                [$start, $end] = explode('-', $part, 2);

                $start = (int) $start;
                $end = (int) $end;

                if ($start > $end) {
                    throw new RuntimeException(
                        'Invalid page range.'
                    );
                }

                if ($start < 1 || $end > $maxPages) {
                    throw new RuntimeException(
                        'Selected page is outside the document range.'
                    );
                }

                $pages = array_merge(
                    $pages,
                    range($start, $end)
                );

                continue;
            }

            $page = (int) $part;

            if ($page < 1 || $page > $maxPages) {
                throw new RuntimeException(
                    'Selected page is outside the document range.'
                );
            }

            $pages[] = $page;
        }

        $pages = array_values(array_unique($pages));

        sort($pages);

        if (count($pages) > 500) {
            throw new RuntimeException(
                'Too many selected pages.'
            );
        }

        return $pages;
    }

    public function normalize(string $input): string
    {
        $input = strtolower(trim($input));

        if ($input === '' || $input === 'all') {
            return 'all';
        }

        $input = str_replace(
            ['–', '—', '−'],
            '-',
            $input
        );

        $input = preg_replace('/\s+/', '', $input);

        $input = preg_replace('/[^0-9,\-]/', '', $input);

        $input = preg_replace('/,{2,}/', ',', $input);

        $input = preg_replace('/-{2,}/', '-', $input);

        $input = trim($input, ',');

        return $input ?: 'all';
    }
}
