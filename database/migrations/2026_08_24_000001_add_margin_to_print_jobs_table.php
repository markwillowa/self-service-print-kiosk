<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('print_jobs', 'margin')) {
                $table->string('margin')
                    ->default('normal')
                    ->after('paper_size');
            }
        });
    }

    public function down(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('print_jobs', 'margin')) {
                $table->dropColumn('margin');
            }
        });
    }
};
