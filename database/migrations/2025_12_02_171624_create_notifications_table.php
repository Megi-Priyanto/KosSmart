<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Relasi tempat kos
            $table->foreignId('tempat_kos_id')
                ->nullable()
                ->constrained('tempat_kos')
                ->cascadeOnDelete();

            // Data notifikasi
            $table->string('type')->default('general');
            $table->string('title')->nullable();
            $table->text('message')->nullable();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('rent_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('room_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
             |--------------------------------------------------
             | PAYMENT & BILLING (gabungan dari migration terpisah)
             |
             | Digunakan untuk notifikasi superadmin ketika admin
             | konfirmasi pembayaran user (pelunasan / bulanan / tahunan).
             | Dana otomatis masuk holding → superadmin cairkan via disbursement.
             |--------------------------------------------------
             */
            $table->unsignedBigInteger('payment_id')
                ->nullable()
                ->comment('ID payment yang dikonfirmasi admin');

            $table->unsignedBigInteger('billing_id')
                ->nullable()
                ->comment('ID billing terkait pembayaran');

            /*
             |--------------------------------------------------
             | DUE DATE (gabungan dari migration terpisah)
             |--------------------------------------------------
             */
            $table->timestamp('due_date')->nullable();

            $table->string('status', 20)->default('unread');

            $table->timestamps();

            // Index
            $table->index('tempat_kos_id');
            $table->index(['user_id', 'status']);
            $table->index('payment_id');
            $table->index('billing_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};