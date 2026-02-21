<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Relasi tempat kos
            $table->foreignId('tempat_kos_id')
                ->constrained('tempat_kos')
                ->cascadeOnDelete();

            // Relasi utama
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('billing_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Data pembayaran
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_sub_method')->nullable();
            $table->text('payment_proof')->nullable();

            // Status pembayaran
            $table->enum('status', ['pending', 'confirmed', 'rejected'])
                ->default('pending');

            // Tanggal & catatan
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();

            // Verifikasi admin
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();

            /*
             |--------------------------------------------------
             | DISBURSEMENT (gabungan dari migration terpisah)
             |
             | disbursement_status:
             |   - 'holding'   : Dana confirmed, masih di platform
             |   - 'disbursed' : Dana sudah dicairkan ke admin kos
             |--------------------------------------------------
             */
            $table->enum('disbursement_status', ['holding', 'disbursed'])
                ->default('holding')
                ->comment('Status pencairan dana ke admin kos');

            $table->unsignedBigInteger('disbursement_id')
                ->nullable()
                ->comment('ID disbursement saat dana dicairkan ke admin');

            // Soft delete & timestamp
            $table->softDeletes();
            $table->timestamps();

            // Index
            $table->index(['tempat_kos_id', 'status']);
            $table->index('billing_id');
            $table->index('user_id');
            $table->index(['disbursement_status', 'status'], 'idx_disbursement_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};