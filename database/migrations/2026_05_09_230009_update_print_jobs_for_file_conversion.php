<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->string('original_file_path')
                ->nullable()
                ->after('original_filename');

            $table->string('converted_pdf_path')
                ->nullable()
                ->after('original_file_path');

            $table->string('original_extension')
                ->nullable()
                ->after('converted_pdf_path');

            $table->string('conversion_status')
                ->default('pending')
                ->after('original_extension');
        });
    }

    public function down(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'original_file_path',
                'converted_pdf_path',
                'original_extension',
                'conversion_status',
            ]);
        });
    }
};
