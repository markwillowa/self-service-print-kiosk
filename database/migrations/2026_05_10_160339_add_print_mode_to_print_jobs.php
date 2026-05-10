<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->string('print_mode')
                ->default('black');

            $table->unsignedInteger('black_price_per_page')
                ->default(1);

            $table->unsignedInteger('color_price_per_page')
                ->default(2);
        });
    }

    public function down(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'print_mode',
                'black_price_per_page',
                'color_price_per_page',
            ]);
        });
    }
};
