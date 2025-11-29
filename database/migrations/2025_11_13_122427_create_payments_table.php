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

            // Relasi user
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Relasi ke billing
            $table->foreignId('billing_id')->nullable()->constrained()->nullOnDelete();

            // Data pembayaran
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->text('payment_proof')->nullable();

            // Status pembayaran
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');

            // Tanggal dan catatan
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();

            // Verifikasi admin
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Soft delete (tambahan)
            $table->softDeletes();

            // created_at & updated_at
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
