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
            $table->string('original_filename');
            $table->string('file_path');
            $table->unsignedInteger('pages')->default(1);
            $table->unsignedInteger('price_per_page')->default(1);
            $table->unsignedInteger('total_amount')->default(1);
            $table->unsignedInteger('paid_amount')->default(0);
            $table->string('status')->default('pending_payment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_jobs');
    }
};
