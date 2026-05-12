<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->string('preview_pdf_path')
                ->nullable()
                ->after('filtered_pdf_path');

            $table->string('orientation')
                ->default('portrait')
                ->after('print_mode');

            $table->string('paper_size')
                ->default('short')
                ->after('orientation');
        });
    }

    public function down(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'preview_pdf_path',
                'orientation',
                'paper_size',
            ]);
        });
    }
};
