<?php

namespace App\Services;

use App\Models\Company;
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
        private readonly FileValidationService $fileValidationService,
        private readonly PdfPreviewGenerator $previewGenerator
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

        $defaultMode = 'black';

        $defaultOrientation = 'portrait';

        $defaultPaperSize = 'short';

        if ($extension === 'pdf') {
            $finalPdfPath = $path;

            $relativePdfPath = $originalPath;
        } else {
            $convertedPdfPath = $this->fileConverter
                ->convertToPdf(
                    path: $path,
                    orientation: $defaultOrientation,
                    paperSize: $defaultPaperSize
                );

            $finalPdfPath = $convertedPdfPath;

            $relativePdfPath =
                'print-jobs/converted/' .
                basename($convertedPdfPath);
        }

        $pages = $this->pdfPageCounter
            ->count($finalPdfPath);

        $previewPdfPath = $this->previewGenerator
            ->generate(
                sourcePath: $finalPdfPath,
                printMode: $defaultMode,
                orientation: $defaultOrientation,
                paperSize: $defaultPaperSize
            );

        $relativePreviewPath =
            'print-jobs/previews/' .
            basename($previewPdfPath);

        $company = Company::query()
            ->latest()
            ->first();

        $blackPricePerPage =
            $company?->black_price_per_page ?? 1;

        $colorPricePerPage =
            $company?->color_price_per_page ?? 3;

        $pricePerPage =
            $defaultMode === 'color'
                ? $colorPricePerPage
                : $blackPricePerPage;

        return PrintJob::create([
            'expires_at' => now()->addMinutes(5),

            'original_filename' => $originalFilename,

            'original_file_path' => $originalPath,

            'converted_pdf_path' => $relativePdfPath,

            'filtered_pdf_path' => null,

            'preview_pdf_path' => $relativePreviewPath,

            'original_extension' => $extension,

            'conversion_status' => 'completed',

            'file_path' => $relativePdfPath,

            'pages' => $pages,

            'page_selection' => 'all',

            'selected_pages_count' => $pages,

            'print_mode' => $defaultMode,

            'orientation' => $defaultOrientation,

            'paper_size' => $defaultPaperSize,

            'black_price_per_page' => $blackPricePerPage,

            'color_price_per_page' => $colorPricePerPage,

            'price_per_page' => $pricePerPage,

            'total_amount' =>
                $pages * $pricePerPage,

            'paid_amount' => 0,

            'status' => 'pending_payment',

            'source' => $source,
        ]);
    }
}
