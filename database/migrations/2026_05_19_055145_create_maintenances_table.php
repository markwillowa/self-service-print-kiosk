<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('admins')
                ->nullOnDelete();
            $table->string('maintenance_type')
                ->default('Inspection');
            $table->string('status')
                ->default('Completed');
            $table->text('issue_reported')
                ->nullable();
            $table->text('action_taken')
                ->nullable();
            $table->text('parts_replaced')
                ->nullable();
            $table->unsignedInteger('cost')
                ->default(0);
            $table->string('printer_status')
                ->nullable();
            $table->string('coin_acceptor_status')
                ->nullable();
            $table->string('paper_stock')
                ->nullable();
            $table->string('ink_status')
                ->nullable();
            $table->string('network_status')
                ->nullable();
            $table->timestamp('performed_at')
                ->nullable();
            $table->timestamp('next_maintenance_at')
                ->nullable();
            $table->text('notes')
                ->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
