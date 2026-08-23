<?php

namespace Tests\Unit;

use App\Services\FileConverter;
use PHPUnit\Framework\TestCase;

class FileConverterLayoutTest extends TestCase
{
    public function test_page_size_twips_for_short_long_and_a4(): void
    {
        $converter = new FileConverter();

        // Short (Letter: 8.5" x 11")
        [$widthShort, $heightShort] = $converter->pageSizeTwips('portrait', 'short');
        $this->assertSame(12240, $widthShort);
        $this->assertSame(15840, $heightShort);

        // Long (Legal: 8.5" x 14")
        [$widthLong, $heightLong] = $converter->pageSizeTwips('portrait', 'long');
        $this->assertSame(12240, $widthLong);
        $this->assertSame(20160, $heightLong);

        // A4 (210mm x 297mm)
        [$widthA4, $heightA4] = $converter->pageSizeTwips('portrait', 'a4');
        $this->assertSame(11906, $widthA4);
        $this->assertSame(16838, $heightA4);

        // Landscape A4
        [$widthA4Land, $heightA4Land] = $converter->pageSizeTwips('landscape', 'a4');
        $this->assertSame(16838, $widthA4Land);
        $this->assertSame(11906, $heightA4Land);
    }

    public function test_margin_twips_for_all_options(): void
    {
        $converter = new FileConverter();

        // Normal (0.25" / 6.35 mm = 360 twips)
        $this->assertSame(360, $converter->marginTwips('normal'));

        // Narrow (0.125" / 3.18 mm = 180 twips)
        $this->assertSame(180, $converter->marginTwips('narrow'));

        // Wide (0.50" / 12.7 mm = 720 twips)
        $this->assertSame(720, $converter->marginTwips('wide'));

        // No Margin (0" = 0 twips)
        $this->assertSame(0, $converter->marginTwips('none'));
        $this->assertSame(0, $converter->marginTwips('no_margin'));

        // Fit to Screen (0 twips)
        $this->assertSame(0, $converter->marginTwips('fit'));
        $this->assertSame(0, $converter->marginTwips('fit_to_screen'));
    }
}
