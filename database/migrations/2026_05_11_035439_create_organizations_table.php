<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')
                ->unique();

            $table->string('school_name');

            $table->string('school_code')
                ->nullable();

            $table->string('contact_person');

            $table->string('contact_number');

            $table->string('email')
                ->nullable();

            $table->text('address');

            $table->string('city')
                ->nullable();

            $table->string('province')
                ->nullable();

            $table->string('region')
                ->nullable();

            $table->string('country')
                ->default('Philippines');

            $table->string('kiosk_name')
                ->default('Piso Print');

            $table->string('unit_serial_number')
                ->unique();

            $table->boolean('is_registered')
                ->default(false);

            $table->timestamp('registered_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
