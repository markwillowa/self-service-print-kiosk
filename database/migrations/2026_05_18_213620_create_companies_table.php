<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->nullable();
            $table->string('kiosk_name')->default('Piso Print');
            $table->string('name');
            $table->string('owner');
            $table->text('address');
            $table->string('email')->nullable();
            $table->string('contact_number');
            $table->unsignedInteger('black_price_per_page')
                ->default(1)
                ->after('kiosk_name');
            $table->unsignedInteger('color_price_per_page')
                ->default(3)
                ->after('black_price_per_page');
            $table->boolean('allow_custom_pricing')
                ->default(false)
                ->after('color_price_per_page');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
