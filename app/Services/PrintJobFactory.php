<?php

namespace App\Services;

use App\Models\Company;
use App\Models\PrintJob;
use App\Support\FilenameSanitizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        $originalPath = $this->storeOriginalFile(
            $file,
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

    public function createUploadedOnly(
        UploadedFile $file,
        string $source = 'mobile'
    ): PrintJob {
        $this->fileValidationService
            ->validate($file);

        $sanitizedFilename = FilenameSanitizer::sanitize(
            $file->getClientOriginalName()
        );

        $originalPath = $this->storeOriginalFile(
            $file,
            $sanitizedFilename
        );

        $extension = strtolower(
            pathinfo($sanitizedFilename, PATHINFO_EXTENSION)
        );

        $company = Company::query()
            ->latest()
            ->first();

        return PrintJob::create([
            'expires_at' => null,
            'original_filename' => $sanitizedFilename,
            'original_file_path' => $originalPath,
            'converted_pdf_path' => null,
            'filtered_pdf_path' => null,
            'preview_pdf_path' => null,
            'original_extension' => $extension,
            'conversion_status' => 'pending',
            'file_path' => $originalPath,
            'pages' => 0,
            'page_selection' => 'all',
            'selected_pages_count' => 0,
            'copies' => 1,
            'print_mode' => 'black',
            'orientation' => 'portrait',
            'paper_size' => 'short',
            'margin' => 'normal',
            'black_price_per_page' => $company?->black_price_per_page ?? 1,
            'color_price_per_page' => $company?->color_price_per_page ?? 3,
            'price_per_page' => $company?->black_price_per_page ?? 1,
            'total_amount' => 0,
            'paid_amount' => 0,
            'status' => 'uploaded',
            'source' => $source,
        ]);
    }

    public function prepareUploadedJob(PrintJob $printJob): PrintJob
    {
        if ($printJob->conversion_status === 'completed') {
            return $printJob;
        }

        $originalPath = $printJob->original_file_path;

        // If the path is absolute (starts with storage_path), convert it to relative for the 'local' disk
        $storageAppPath = storage_path('app/');
        if (str_starts_with($originalPath, $storageAppPath)) {
            $originalPath = str_replace($storageAppPath, '', $originalPath);
        }

        if (!Storage::disk('local')->exists($originalPath)) {
            logger()->error('File missing in storage', [
                'print_job_id' => $printJob->id,
                'original_file_path' => $originalPath,
            ]);
            throw new RuntimeException('File does not exist in storage.');
        }

        $originalFullPath = Storage::disk('local')
            ->path($originalPath);

        $preparedJob = $this->createFromPath(
            path: $originalFullPath,
            originalFilename: $printJob->original_filename,
            originalPath: $printJob->original_file_path,
            source: $printJob->source ?? 'mobile'
        );

        $printJob->update([
            'expires_at' => now()->addMinutes(5),
            'converted_pdf_path' => $preparedJob->converted_pdf_path,
            'filtered_pdf_path' => null,
            'preview_pdf_path' => $preparedJob->preview_pdf_path,
            'conversion_status' => 'completed',
            'file_path' => $preparedJob->file_path,
            'pages' => $preparedJob->pages,
            'page_selection' => 'all',
            'selected_pages_count' => $preparedJob->selected_pages_count,
            'copies' => $preparedJob->copies,
            'print_mode' => $preparedJob->print_mode,
            'orientation' => $preparedJob->orientation,
            'paper_size' => $preparedJob->paper_size,
            'margin' => $preparedJob->margin ?? 'normal',
            'black_price_per_page' => $preparedJob->black_price_per_page,
            'color_price_per_page' => $preparedJob->color_price_per_page,
            'price_per_page' => $preparedJob->price_per_page,
            'total_amount' => $preparedJob->total_amount,
            'paid_amount' => 0,
            'status' => 'pending_payment',
        ]);

        $preparedJob->delete();

        return $printJob->refresh();
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

        $defaultMargin = 'normal';

        $defaultCopies = 1;

        if ($extension === 'pdf') {
            $finalPdfPath = $path;

            $relativePdfPath = $originalPath;
        } else {
            $convertedPdfPath = $this->fileConverter
                ->convertToPdf(
                    path: $path,
                    orientation: $defaultOrientation,
                    paperSize: $defaultPaperSize,
                    margin: $defaultMargin
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
                paperSize: $defaultPaperSize,
                margin: $defaultMargin
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

        $totalAmount =
            $pages *
            $defaultCopies *
            $pricePerPage;

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

            'copies' => $defaultCopies,

            'print_mode' => $defaultMode,

            'orientation' => $defaultOrientation,

            'paper_size' => $defaultPaperSize,

            'margin' => $defaultMargin,

            'black_price_per_page' => $blackPricePerPage,

            'color_price_per_page' => $colorPricePerPage,

            'price_per_page' => $pricePerPage,

            'total_amount' => $totalAmount,

            'paid_amount' => 0,

            'status' => 'pending_payment',

            'source' => $source,
        ]);
    }

    private function storeOriginalFile(
        UploadedFile $file,
        string $sanitizedFilename
    ): string {
        $storedFilename = Str::uuid() . '-' . $sanitizedFilename;

        $originalPath = $file->storeAs(
            'print-jobs/original',
            $storedFilename,
            'local'
        );

        $storageAppPath = storage_path('app/');
        if (str_starts_with($originalPath, $storageAppPath)) {
            return str_replace($storageAppPath, '', $originalPath);
        }

        return $originalPath;
    }
}
