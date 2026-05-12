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

        $originalPath = $file->store(
            'print-jobs/original'
        );

        $originalFullPath = Storage::disk('local')
            ->path($originalPath);

        return $this->createFromPath(
            path: $originalFullPath,
            originalFilename: FilenameSanitizer::sanitize(
                $file->getClientOriginalName()
            ),
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

        $pages = $this->pdfPageCounter
            ->count($finalPdfPath);

        $pricePerPage = 1;

        return PrintJob::create([
            'expires_at' => now()->addMinutes(5),

            'original_filename' => $originalFilename,

            'original_file_path' => $originalPath,

            'converted_pdf_path' => $relativePdfPath,

            'filtered_pdf_path' => null,

            'original_extension' => $extension,

            'conversion_status' => 'completed',

            'file_path' => $relativePdfPath,

            'pages' => $pages,

            'page_selection' => 'all',

            'selected_pages_count' => $pages,

            'print_mode' => 'black',

            'black_price_per_page' => 1,

            'color_price_per_page' => 2,

            'price_per_page' => 1,

            'total_amount' => $pages * $pricePerPage,

            'paid_amount' => 0,

            'status' => 'pending_payment',

            'source' => $source,
        ]);
    }
}
