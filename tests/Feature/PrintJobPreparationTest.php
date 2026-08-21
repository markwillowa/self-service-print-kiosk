<?php

namespace Tests\Feature;

use App\Models\PrintJob;
use App\Services\PrintJobFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Mockery;
use App\Services\PdfPageCounter;
use App\Services\PdfPreviewGenerator;
use App\Services\PdfValidationService;
use App\Services\ImageValidationService;
use App\Services\FileConverter;

class PrintJobPreparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploaded_file_can_be_prepared()
    {
        Storage::fake('local');

        // Mock dependencies
        $mockPdfPageCounter = Mockery::mock(PdfPageCounter::class);
        $mockPdfPageCounter->shouldReceive('count')->andReturn(1);
        $this->app->instance(PdfPageCounter::class, $mockPdfPageCounter);

        $mockPreviewGenerator = Mockery::mock(PdfPreviewGenerator::class);
        $mockPreviewGenerator->shouldReceive('generate')->andReturn(storage_path('app/print-jobs/previews/preview.pdf'));
        $this->app->instance(PdfPreviewGenerator::class, $mockPreviewGenerator);

        $mockPdfValidationService = Mockery::mock(PdfValidationService::class);
        $mockPdfValidationService->shouldReceive('validate')->andReturnNull();
        $this->app->instance(PdfValidationService::class, $mockPdfValidationService);

        $mockImageValidationService = Mockery::mock(ImageValidationService::class);
        $mockImageValidationService->shouldReceive('validate')->andReturnNull();
        $this->app->instance(ImageValidationService::class, $mockImageValidationService);

        $mockFileConverter = Mockery::mock(FileConverter::class);
        $mockFileConverter->shouldReceive('convertToPdf')->andReturn(storage_path('app/print-jobs/converted/test.pdf'));
        $this->app->instance(FileConverter::class, $mockFileConverter);

        // Create an uploaded file
        $file = UploadedFile::fake()->image('test.png');

        $factory = app(PrintJobFactory::class);

        // 1. Initial upload
        $printJob = $factory->createUploadedOnly($file);

        $this->assertEquals('uploaded', $printJob->status);
        $this->assertNotNull($printJob->original_file_path);

        // Ensure file exists in fake storage
        Storage::disk('local')->assertExists($printJob->original_file_path);

        // Verify it's a relative path even if we didn't mock storeAs to return absolute
        $this->assertFalse(str_starts_with($printJob->original_file_path, storage_path()));

        // SIMULATE ABSOLUTE PATH IN DB (to test prepareUploadedJob resilience)
        $printJob->update(['original_file_path' => storage_path('app/') . $printJob->original_file_path]);

        // 2. Prepare the job
        $preparedJob = $factory->prepareUploadedJob($printJob);

        $this->assertEquals('pending_payment', $preparedJob->status);
        $this->assertEquals('completed', $preparedJob->conversion_status);
    }

    public function test_uploaded_files_with_same_original_name_are_stored_separately()
    {
        Storage::fake('local');

        $firstFile = UploadedFile::fake()->image('same-name.png');
        $secondFile = UploadedFile::fake()->image('same-name.png');

        $factory = app(PrintJobFactory::class);

        $firstJob = $factory->createUploadedOnly($firstFile);
        $secondJob = $factory->createUploadedOnly($secondFile);

        $this->assertSame('same-name.png', $firstJob->original_filename);
        $this->assertSame('same-name.png', $secondJob->original_filename);
        $this->assertNotSame(
            $firstJob->original_file_path,
            $secondJob->original_file_path
        );

        Storage::disk('local')->assertExists($firstJob->original_file_path);
        Storage::disk('local')->assertExists($secondJob->original_file_path);
    }
}
