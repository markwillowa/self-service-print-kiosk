<?php

namespace App\Services;

use App\Models\PrintJob;
use App\Support\FilenameSanitizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PrintJobFactory
{
    public function __construct(
        private readonly PdfPageCounter $pdfPageCounter,
        private readonly FileConverter $fileConverter,
        private readonly FileValidationService $fileValidationService
    ) {
    }

    public function createFromUploadedFile(
        UploadedFile $file,
        string $source = 'upload'
    ): PrintJob {
        $this->fileValidationService
            ->validate($file);

        $sanitizedFilename = FilenameSanitizer::sanitize(
            $file->getClientOriginalName()
        );

        $originalPath = $file->storeAs(
            'print-jobs/original',
            $sanitizedFilename
        );

        $originalFullPath = Storage::disk('local')
            ->path($originalPath);

        return $this->createFromPath(
            path: $originalFullPath,

            originalFilename: $sanitizedFilename,

            originalPath: $originalPath,

            source: $source
        );
    }

    public function createFromPath(
        string $path,
        string $originalFilename,
        string $originalPath,
        string $source = 'bluetooth'
    ): PrintJob {
        if (! file_exists($path)) {
            throw new RuntimeException(
                'File does not exist.'
            );
        }

        $extension = strtolower(
            pathinfo(
                $originalFilename,
                PATHINFO_EXTENSION
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Convert To PDF
        |--------------------------------------------------------------------------
        */

        if ($extension === 'pdf') {
            $finalPdfPath = $path;

            $relativePdfPath = $originalPath;
        } else {
            $convertedPdfPath = $this->fileConverter
                ->convertToPdf($path);

            $finalPdfPath = $convertedPdfPath;

            $relativePdfPath =
                'print-jobs/converted/' .
                basename($convertedPdfPath);
        }

        /*
        |--------------------------------------------------------------------------
        | Count Pages
        |--------------------------------------------------------------------------
        */

        $pages = $this->pdfPageCounter
            ->count($finalPdfPath);

        /*
        |--------------------------------------------------------------------------
        | Default Pricing
        |--------------------------------------------------------------------------
        */

        $blackPricePerPage = 1;

        $colorPricePerPage = 2;

        $defaultMode = 'black';

        $pricePerPage =
            $defaultMode === 'color'
                ? $colorPricePerPage
                : $blackPricePerPage;

        /*
        |--------------------------------------------------------------------------
        | Create Print Job
        |--------------------------------------------------------------------------
        */

        return PrintJob::create([
            'expires_at' => now()->addMinutes(5),

            /*
            |--------------------------------------------------------------------------
            | File Information
            |--------------------------------------------------------------------------
            */

            'original_filename' => $originalFilename,

            'original_file_path' => $originalPath,

            'converted_pdf_path' => $relativePdfPath,

            'filtered_pdf_path' => null,

            'preview_pdf_path' => null,

            'original_extension' => $extension,

            'conversion_status' => 'completed',

            'file_path' => $relativePdfPath,

            /*
            |--------------------------------------------------------------------------
            | Page Settings
            |--------------------------------------------------------------------------
            */

            'pages' => $pages,

            'page_selection' => 'all',

            'selected_pages_count' => $pages,

            /*
            |--------------------------------------------------------------------------
            | Print Settings
            |--------------------------------------------------------------------------
            */

            'print_mode' => $defaultMode,

            'orientation' => 'portrait',

            'paper_size' => 'short',

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            'black_price_per_page' => $blackPricePerPage,

            'color_price_per_page' => $colorPricePerPage,

            'price_per_page' => $pricePerPage,

            'total_amount' =>
                $pages * $pricePerPage,

            'paid_amount' => 0,

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => 'pending_payment',

            'source' => $source,
        ]);
    }
}
