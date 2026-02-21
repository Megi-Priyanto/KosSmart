<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel disbursements — mencatat proses pencairan dana dari Superadmin ke Admin kos.
     * Satu disbursement bisa mencakup banyak payments (batch).
     *
     * Logika pencairan:
     *   gross_amount = total dari semua payment user
     *   fee_percent  = % potongan platform (diset superadmin saat cairkan)
     *   fee_amount   = gross_amount * fee_percent / 100  → pendapatan superadmin
     *   total_amount = gross_amount - fee_amount          → yang diterima admin kos
     */
    public function up(): void
    {
        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('tempat_kos_id')
                ->constrained('tempat_kos')
                ->cascadeOnDelete()
                ->comment('Kos yang menerima pencairan dana');

            $table->foreignId('admin_id')
                ->constrained('users')
                ->comment('Admin pemilik kos yang menerima dana');

            /*
             |--------------------------------------------------
             | DATA PENCAIRAN + FEE (gabungan dari migration terpisah)
             |--------------------------------------------------
             */
            $table->decimal('gross_amount', 15, 2)->default(0)
                ->comment('Total bruto dari payment user sebelum dipotong fee');

            $table->decimal('fee_percent', 5, 2)->default(10.00)
                ->comment('Persentase fee platform (%)');

            $table->decimal('fee_amount', 15, 2)->default(0)
                ->comment('Nominal fee platform = gross_amount * fee_percent / 100');

            $table->decimal('total_amount', 15, 2)
                ->comment('Total dana yang diterima admin = gross_amount - fee_amount');

            $table->integer('payment_count')->default(0)
                ->comment('Jumlah payment yang dicairkan dalam batch ini');

            // Periode billing (opsional)
            $table->string('period_from', 7)->nullable()->comment('Periode awal pencairan (YYYY-MM)');
            $table->string('period_to', 7)->nullable()->comment('Periode akhir pencairan (YYYY-MM)');

            $table->text('description')->nullable()->comment('Catatan pencairan dari Superadmin');

            // Status & bukti
            $table->enum('status', ['pending', 'processed'])
                ->default('pending')
                ->comment('Status pencairan dana');

            $table->string('transfer_method')->nullable()->comment('Metode transfer ke admin');
            $table->string('transfer_account')->nullable()->comment('Nomor rekening admin tujuan');
            $table->string('transfer_proof')->nullable()->comment('Bukti transfer ke admin');

            // Proses
            $table->timestamp('processed_at')->nullable()->comment('Waktu dana dicairkan');

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Superadmin yang memproses pencairan');

            // Timestamp & soft delete
            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['tempat_kos_id', 'status'], 'idx_disbursement_kos_status');
            $table->index('admin_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disbursements');
    }
};