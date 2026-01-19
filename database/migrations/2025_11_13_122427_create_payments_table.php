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

            // ================= RELASI =================
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('billing_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // ================= DATA PEMBAYARAN =================
            $table->decimal('amount', 10, 2);

            $table->string('payment_method')->nullable();      // bank / e-wallet
            $table->string('payment_type')->nullable();        // transfer / cash
            $table->string('payment_sub_method')->nullable();  // BCA / DANA / dll

            $table->text('payment_proof')->nullable();

            // ================= STATUS =================
            $table->enum('status', ['pending', 'confirmed', 'rejected'])
                ->default('pending');

            // ================= TANGGAL & CATATAN =================
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();

            // ================= VERIFIKASI ADMIN =================
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // ================= SOFT DELETE =================
            $table->softDeletes();

            // ================= TIMESTAMP =================
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
