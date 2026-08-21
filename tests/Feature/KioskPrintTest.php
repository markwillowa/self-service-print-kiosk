<?php

namespace Tests\Feature;

use App\Models\PrintJob;
use App\Http\Middleware\EnsureKioskRegistered;
use App\Http\Middleware\RestrictKioskAccess;
use App\Services\PrinterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}