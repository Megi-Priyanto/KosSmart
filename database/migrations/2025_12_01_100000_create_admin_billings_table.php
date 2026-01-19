<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_billings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tempat_kos_id')
                ->constrained('tempat_kos')
                ->cascadeOnDelete();

            $table->foreignId('admin_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Periode
            $table->string('billing_period'); // 2026-01
            $table->integer('billing_month');
            $table->integer('billing_year');

            // Nominal
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->date('due_date');

            // Status pembayaran
            $table->enum('status', ['unpaid', 'pending', 'paid', 'overdue'])
                ->default('unpaid');

            // Pembayaran
            $table->string('payment_proof')->nullable();
            $table->text('payment_notes')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Verifikasi
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['tempat_kos_id', 'status']);
            $table->index(['admin_id', 'billing_period']);
            $table->unique(['tempat_kos_id', 'billing_period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_billings');
    }
};
