<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureKioskRegistered;
use App\Http\Middleware\RestrictKioskAccess;
use App\Models\Company;
use App\Models\PrintJob;
use App\Services\FileConverter;
use App\Services\PdfPageCounter;
use App\Services\PdfPageExtractor;
use App\Services\PdfPreviewGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class DocumentEditSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            EnsureKioskRegistered::class,
            RestrictKioskAccess::class,
        ]);

        Company::create([
            'name' => 'Test School',
            'owner' => 'Admin',
            'email' => 'admin@test.com',
            'contact_number' => '09123456789',
            'address' => 'Test Address',
            'kiosk_name' => 'Piso Print',
            'black_price_per_page' => 1,
            'color_price_per_page' => 3,
        ]);
    }

    public function test_preview_page_displays_paper_size_and_margin_options(): void
    {
        Storage::fake('local');

        $printJob = PrintJob::create([
            'original_filename' => 'sample.pdf',
            'original_file_path' => 'print-jobs/original/sample.pdf',
            'converted_pdf_path' => 'print-jobs/converted/sample.pdf',
            'preview_pdf_path' => 'print-jobs/previews/sample.pdf',
            'file_path' => 'print-jobs/original/sample.pdf',
            'original_extension' => 'pdf',
            'conversion_status' => 'completed',
            'pages' => 3,
            'page_selection' => 'all',
            'selected_pages_count' => 3,
            'copies' => 1,
            'print_mode' => 'black',
            'orientation' => 'portrait',
            'paper_size' => 'a4',
            'margin' => 'normal',
            'black_price_per_page' => 1,
            'color_price_per_page' => 3,
            'price_per_page' => 1,
            'total_amount' => 3,
            'paid_amount' => 0,
            'status' => 'uploaded',
            'source' => 'usb',
        ]);

        $response = $this->get(route('kiosk.preview', $printJob));

        $response->assertStatus(200);
        $response->assertSee('A4');
        $response->assertSee('Margin');
        $response->assertSee('Normal ⭐ (0.25" / 6.35 mm)', false);
        $response->assertSee('Narrow (0.125" / 3.18 mm)', false);
        $response->assertSee('Wide (0.50" / 12.7 mm)', false);
        $response->assertSee('No Margin (0")', false);
        $response->assertSee('Fit to Screen');
        $response->assertSee('view=Fit');
    }

    public function test_user_can_update_paper_size_to_a4_and_margin_to_narrow(): void
    {
        Storage::fake('local');

        $workingPdfPath = storage_path('app/print-jobs/original/sample.pdf');
        @mkdir(dirname($workingPdfPath), 0777, true);
        file_put_contents($workingPdfPath, '%PDF-1.4 sample');

        $mockPdfPageCounter = Mockery::mock(PdfPageCounter::class);
        $mockPdfPageCounter->shouldReceive('count')->andReturn(2);
        $this->app->instance(PdfPageCounter::class, $mockPdfPageCounter);

        $mockPreviewGenerator = Mockery::mock(PdfPreviewGenerator::class);
        $mockPreviewGenerator->shouldReceive('generate')->andReturn(storage_path('app/print-jobs/previews/preview.pdf'));
        $this->app->instance(PdfPreviewGenerator::class, $mockPreviewGenerator);

        $printJob = PrintJob::create([
            'original_filename' => 'sample.pdf',
            'original_file_path' => 'print-jobs/original/sample.pdf',
            'converted_pdf_path' => 'print-jobs/converted/sample.pdf',
            'preview_pdf_path' => 'print-jobs/previews/sample.pdf',
            'file_path' => 'print-jobs/original/sample.pdf',
            'original_extension' => 'pdf',
            'conversion_status' => 'completed',
            'pages' => 2,
            'page_selection' => 'all',
            'selected_pages_count' => 2,
            'copies' => 1,
            'print_mode' => 'black',
            'orientation' => 'portrait',
            'paper_size' => 'short',
            'margin' => 'normal',
            'black_price_per_page' => 1,
            'color_price_per_page' => 3,
            'price_per_page' => 1,
            'total_amount' => 2,
            'paid_amount' => 0,
            'status' => 'uploaded',
            'source' => 'usb',
        ]);

        $response = $this->post(route('kiosk.update-settings', $printJob), [
            'print_mode' => 'color',
            'orientation' => 'landscape',
            'paper_size' => 'a4',
            'margin' => 'narrow',
            'copies' => 2,
            'page_selection' => '',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $printJob->refresh();

        $this->assertSame('a4', $printJob->paper_size);
        $this->assertSame('narrow', $printJob->margin);
        $this->assertSame('landscape', $printJob->orientation);
        $this->assertSame('color', $printJob->print_mode);
        $this->assertSame(2, $printJob->copies);
        $this->assertSame(12, $printJob->total_amount); // 2 pages * 2 copies * 3 price_per_page
    }

    public function test_margin_and_paper_size_options_and_normalizations(): void
    {
        Storage::fake('local');

        $workingPdfPath = storage_path('app/print-jobs/original/sample.pdf');
        @mkdir(dirname($workingPdfPath), 0777, true);
        file_put_contents($workingPdfPath, '%PDF-1.4 sample');

        $mockPdfPageCounter = Mockery::mock(PdfPageCounter::class);
        $mockPdfPageCounter->shouldReceive('count')->andReturn(1);
        $this->app->instance(PdfPageCounter::class, $mockPdfPageCounter);

        $mockPreviewGenerator = Mockery::mock(PdfPreviewGenerator::class);
        $mockPreviewGenerator->shouldReceive('generate')->andReturn(storage_path('app/print-jobs/previews/preview.pdf'));
        $this->app->instance(PdfPreviewGenerator::class, $mockPreviewGenerator);

        $printJob = PrintJob::create([
            'original_filename' => 'sample.pdf',
            'original_file_path' => 'print-jobs/original/sample.pdf',
            'converted_pdf_path' => 'print-jobs/converted/sample.pdf',
            'preview_pdf_path' => 'print-jobs/previews/sample.pdf',
            'file_path' => 'print-jobs/original/sample.pdf',
            'original_extension' => 'pdf',
            'conversion_status' => 'completed',
            'pages' => 1,
            'page_selection' => 'all',
            'selected_pages_count' => 1,
            'copies' => 1,
            'print_mode' => 'black',
            'orientation' => 'portrait',
            'paper_size' => 'short',
            'margin' => 'normal',
            'black_price_per_page' => 1,
            'color_price_per_page' => 3,
            'price_per_page' => 1,
            'total_amount' => 1,
            'paid_amount' => 0,
            'status' => 'uploaded',
            'source' => 'usb',
        ]);

        // Test wide margin
        $this->post(route('kiosk.update-settings', $printJob), [
            'print_mode' => 'black',
            'orientation' => 'portrait',
            'paper_size' => 'long',
            'margin' => 'wide',
            'copies' => 1,
            'page_selection' => 'all',
        ])->assertSessionHasNoErrors();
        $printJob->refresh();
        $this->assertSame('wide', $printJob->margin);
        $this->assertSame('long', $printJob->paper_size);

        // Test no_margin normalizes to none
        $this->post(route('kiosk.update-settings', $printJob), [
            'print_mode' => 'black',
            'orientation' => 'portrait',
            'paper_size' => 'a4',
            'margin' => 'no_margin',
            'copies' => 1,
            'page_selection' => 'all',
        ])->assertSessionHasNoErrors();
        $printJob->refresh();
        $this->assertSame('none', $printJob->margin);
        $this->assertSame('a4', $printJob->paper_size);

        // Test fit_to_screen normalizes to fit
        $this->post(route('kiosk.update-settings', $printJob), [
            'print_mode' => 'black',
            'orientation' => 'portrait',
            'paper_size' => 'a4',
            'margin' => 'fit_to_screen',
            'copies' => 1,
            'page_selection' => 'all',
        ])->assertSessionHasNoErrors();
        $printJob->refresh();
        $this->assertSame('fit', $printJob->margin);
    }

    public function test_preview_file_serves_pdf_with_valid_signed_url(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('print-jobs/previews/sample.pdf', '%PDF-1.4 test preview');

        $printJob = PrintJob::create([
            'original_filename' => 'sample.pdf',
            'original_file_path' => 'print-jobs/original/sample.pdf',
            'preview_pdf_path' => 'print-jobs/previews/sample.pdf',
            'file_path' => 'print-jobs/original/sample.pdf',
            'original_extension' => 'pdf',
            'conversion_status' => 'completed',
            'pages' => 1,
            'status' => 'uploaded',
            'source' => 'usb',
        ]);

        $previewUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'kiosk.preview-file',
            now()->addMinutes(15),
            [
                'printJob' => $printJob,
                'v' => time(),
            ],
            absolute: false
        );

        $response = $this->get($previewUrl);
        $response->assertStatus(200);

        // Accessing without signature should return 403
        $unsignedUrl = route('kiosk.preview-file', $printJob);
        $unsignedResponse = $this->get($unsignedUrl);
        $unsignedResponse->assertStatus(403);
    }
}

