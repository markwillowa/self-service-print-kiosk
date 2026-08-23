<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')
                ->nullable()
                ->unique();
            $table->string('original_filename');
            $table->string('original_file_path')
                ->nullable();
            $table->string('converted_pdf_path')
                ->nullable();
            $table->string('filtered_pdf_path')
                ->nullable();
            $table->string('preview_pdf_path')
                ->nullable();
            $table->string('original_extension')
                ->nullable();
            $table->string('conversion_status')
                ->default('pending');
            $table->string('file_path');
            $table->unsignedInteger('pages')
                ->default(1);
            $table->string('page_selection')
                ->nullable();
            $table->unsignedInteger('selected_pages_count')
                ->default(0);
            $table->unsignedInteger('price_per_page')
                ->default(1);
            $table->unsignedInteger('total_amount')
                ->default(1);
            $table->unsignedInteger('paid_amount')
                ->default(0);
            $table->string('status')
                ->default('pending_payment');
            $table->string('source')
                ->default('upload');
            $table->timestamp('expires_at')
                ->nullable();
            $table->timestamp('cancelled_at')
                ->nullable();
            $table->timestamp('completed_at')
                ->nullable();
            $table->string('print_mode')
                ->default('black');
            $table->string('orientation')
                ->default('portrait');
            $table->string('paper_size')
                ->default('short');
            $table->string('margin')
                ->default('normal');
            $table->unsignedInteger('copies')
                ->default(1);
            $table->unsignedInteger('black_price_per_page')
                ->default(1);
            $table->unsignedInteger('color_price_per_page')
                ->default(2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_jobs');
    }
};
