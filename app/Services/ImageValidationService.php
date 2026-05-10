<?php

namespace App\Services;

use RuntimeException;

class ImageValidationService
{
    private const MAX_WIDTH = 10000;

    private const MAX_HEIGHT = 10000;

    private const MAX_PIXELS = 40000000;

    public function validate(string $path): void
    {
        $imageInfo = getimagesize($path);

        if ($imageInfo === false) {
            throw new RuntimeException(
                'Invalid image file.'
            );
        }

        [$width, $height] = $imageInfo;

        if ($width > self::MAX_WIDTH) {
            throw new RuntimeException(
                'Image width exceeds limit.'
            );
        }

        if ($height > self::MAX_HEIGHT) {
            throw new RuntimeException(
                'Image height exceeds limit.'
            );
        }

        if (($width * $height) > self::MAX_PIXELS) {
            throw new RuntimeException(
                'Image resolution too large.'
            );
        }
    }
}
