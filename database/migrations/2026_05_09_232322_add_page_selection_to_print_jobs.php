<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->string('page_selection')
                ->nullable()
                ->after('pages');

            $table->unsignedInteger('selected_pages_count')
                ->default(0)
                ->after('page_selection');
        });
    }

    public function down(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'page_selection',
                'selected_pages_count',
            ]);
        });
    }
};
