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

            // Alasan Cancel dari User
            $table->text('cancel_reason')->nullable();

            // Catatan Admin saat menyetujui (diteruskan ke superadmin)
            $table->text('admin_approval_notes')->nullable();

            // Status alur baru:
            // pending          → User ajukan, menunggu admin
            // admin_approved   → Admin setuju, menunggu superadmin refund
            // approved         → Superadmin sudah transfer refund ke user
            // rejected         → Admin menolak
            $table->enum('status', ['pending', 'admin_approved', 'approved', 'rejected'])->default('pending');

            // Data Refund oleh Superadmin
            $table->string('refund_method')->nullable();         // manual_transfer / e_wallet
            $table->string('refund_sub_method')->nullable();    // BCA / DANA / dll
            $table->string('refund_account_number')->nullable();
            $table->decimal('refund_amount', 12, 2)->nullable();
            $table->text('refund_proof')->nullable();           // Bukti transfer dari superadmin
            $table->text('admin_notes')->nullable();            // Catatan saat tolak / proses refund

            // Admin yang menyetujui (admin kos)
            $table->foreignId('approved_by_admin')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('admin_approved_at')->nullable();

            // Superadmin yang memproses refund
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