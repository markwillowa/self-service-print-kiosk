<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->timestamp('expires_at')
                ->nullable()
                ->after('status');

            $table->timestamp('cancelled_at')
                ->nullable()
                ->after('expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->dropColumn([
                'expires_at',
                'cancelled_at',
            ]);
        });
    }
};
