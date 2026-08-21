<?php

namespace Tests\Feature;

use App\Models\PrintJob;
use App\Http\Middleware\EnsureKioskRegistered;
use App\Http\Middleware\RestrictKioskAccess;
use App\Services\PrinterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KioskPrintTest extends TestCase
{
    use RefreshDatabase;

    public function test_paid_print_job_is_processed_when_print_button_is_pressed(): void
    {
        $this->withoutExceptionHandling();

        config([
            'app.key' => str_repeat('a', 32),
            'queue.default' => 'database',
        ]);

        $this->app->bind(
            PrinterService::class,
            fn () => new class extends PrinterService
            {
                public function __construct()
                {
                }

                public function print(PrintJob $printJob): bool
                {
                    $printJob->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                    return true;
                }
            }
        );

        $printJob = PrintJob::create([
            'original_filename' => 'document.pdf',
            'file_path' => 'documents/document.pdf',
            'selected_pages_count' => 1,
            'total_amount' => 1,
            'paid_amount' => 1,
            'status' => 'paid',
        ]);

        $this->assertSame('paid', $printJob->refresh()->status);

        $this->withoutMiddleware([
            EnsureKioskRegistered::class,
            RestrictKioskAccess::class,
        ])
            ->post(route('kiosk.print', $printJob))
            ->assertRedirect(route('kiosk.status', $printJob));

        $this->assertSame(
            'completed',
            $printJob->refresh()->status
        );
    }

    public function test_upload_page_hides_missing_uploaded_files(): void
    {
        Storage::fake('local');

        $staleJob = $this->createUploadedJob(
            'missing.pdf',
            'print-jobs/original/missing.pdf'
        );

        $availableJob = $this->createUploadedJob(
            'available.pdf',
            'print-jobs/original/available.pdf'
        );

        Storage::disk('local')->put(
            $availableJob->original_file_path,
            'available file'
        );

        $this->withoutMiddleware([
            EnsureKioskRegistered::class,
            RestrictKioskAccess::class,
        ])
            ->get(route('kiosk.upload'))
            ->assertOk()
            ->assertDontSee($staleJob->original_filename)
            ->assertSee($availableJob->original_filename);

        $this->assertSame('cancelled', $staleJob->refresh()->status);
        $this->assertSame('uploaded', $availableJob->refresh()->status);
    }

    public function test_selecting_missing_uploaded_file_cancels_it(): void
    {
        Storage::fake('local');

        $printJob = $this->createUploadedJob(
            'missing.pdf',
            'print-jobs/original/missing.pdf'
        );

        $this->withoutMiddleware([
            EnsureKioskRegistered::class,
            RestrictKioskAccess::class,
        ])
            ->post(route('kiosk.select-upload', $printJob))
            ->assertRedirect(route('kiosk.upload'))
            ->assertSessionHasErrors('document');

        $this->assertSame('cancelled', $printJob->refresh()->status);
        $this->assertNotNull($printJob->cancelled_at);
    }

    private function createUploadedJob(
        string $filename,
        string $path
    ): PrintJob {
        return PrintJob::create([
            'original_filename' => $filename,
            'original_file_path' => $path,
            'original_extension' => pathinfo($filename, PATHINFO_EXTENSION),
            'conversion_status' => 'pending',
            'file_path' => $path,
            'pages' => 0,
            'selected_pages_count' => 0,
            'total_amount' => 0,
            'paid_amount' => 0,
            'status' => 'uploaded',
            'source' => 'mobile',
        ]);
    }
}