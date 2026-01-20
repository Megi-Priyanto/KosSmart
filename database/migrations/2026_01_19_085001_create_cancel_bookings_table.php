<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cancel_bookings', function (Blueprint $table) {
            $table->id();
            
            // Relasi
            $table->foreignId('tempat_kos_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rent_id')->constrained('rents')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Data Rekening User untuk Refund
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder_name');
            
            // Alasan Cancel
            $table->text('cancel_reason')->nullable();
            
            // Status: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Data Refund oleh Admin
            $table->string('refund_method')->nullable(); // manual_transfer / e_wallet
            $table->string('refund_sub_method')->nullable(); // BCA / DANA / dll
            $table->string('refund_account_number')->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->text('refund_proof')->nullable(); // Bukti transfer dari admin
            $table->text('admin_notes')->nullable();
            
            // Verifikasi
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index(['user_id', 'status']);
            $table->index('rent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cancel_bookings');
    }
};