<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use RuntimeException;

class FileValidationService
{
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',

        'application/msword',

        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

        'application/vnd.ms-excel',

        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

        'application/vnd.ms-powerpoint',

        'application/vnd.openxmlformats-officedocument.presentationml.presentation',

        'image/jpeg',

        'image/png',

        'text/plain',
    ];

    private const ALLOWED_EXTENSIONS = [
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
        'jpg',
        'jpeg',
        'png',
        'txt',
    ];

    public function __construct(
        private ImageValidationService $imageValidationService,
        private PdfValidationService $pdfValidationService
    ) {
    }

    public function validate(UploadedFile $file): void
    {
        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            throw new RuntimeException(
                'Unsupported file extension.'
            );
        }

        $mimeType = $file->getMimeType();

        if (! in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            throw new RuntimeException(
                'Invalid file MIME type.'
            );
        }

        $originalName = strtolower(
            $file->getClientOriginalName()
        );

        if (
            str_contains($originalName, '.php') ||
            str_contains($originalName, '.exe') ||
            str_contains($originalName, '.sh') ||
            str_contains($originalName, '.bat') ||
            str_contains($originalName, '.js')
        ) {
            throw new RuntimeException(
                'Dangerous filename detected.'
            );
        }

        if (
            str_ends_with($originalName, '.docm') ||
            str_ends_with($originalName, '.xlsm') ||
            str_ends_with($originalName, '.pptm')
        ) {
            throw new RuntimeException(
                'Macro-enabled documents are not allowed.'
            );
        }

        if (
            $this->containsDangerousFilenameChars(
                $originalName
            )
        ) {
            throw new RuntimeException(
                'Invalid filename.'
            );
        }

        if ($file->getSize() > 100 * 1024 * 1024) {
            throw new RuntimeException(
                'File exceeds maximum size.'
            );
        }

        if (
            in_array($extension, ['xlsx', 'xls'], true) &&
            $file->getSize() > 25 * 1024 * 1024
        ) {
            throw new RuntimeException(
                'Spreadsheet exceeds maximum size.'
            );
        }

        if (
            in_array($extension, ['docx', 'doc'], true) &&
            $file->getSize() > 50 * 1024 * 1024
        ) {
            throw new RuntimeException(
                'Document exceeds maximum size.'
            );
        }

        $path = $file->getRealPath();

        if (! $path) {
            throw new RuntimeException(
                'Unable to access uploaded file.'
            );
        }

        if (
            in_array(
                $extension,
                ['jpg', 'jpeg', 'png'],
                true
            )
        ) {
            $this->imageValidationService
                ->validate($path);
        }

        if ($extension === 'pdf') {
            $this->pdfValidationService
                ->validate($path);

            $this->validatePdfContents($path);
        }
    }

    private function validatePdfContents(
        string $path
    ): void {
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException(
                'Unable to read PDF.'
            );
        }

        $dangerousPatterns = [
            '/JavaScript',
            '/JS',
            '/Launch',
            '/OpenAction',
            '/EmbeddedFile',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (str_contains($contents, $pattern)) {
                throw new RuntimeException(
                    'Dangerous PDF content detected.'
                );
            }
        }
    }

    private function containsDangerousFilenameChars(
        string $filename
    ): bool {
        return preg_match(
                '/[^A-Za-z0-9\\-_\\.\\s]/',
                $filename
            ) === 1;
    }
}
