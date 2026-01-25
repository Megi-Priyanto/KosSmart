<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->id();

            /*
             |---------------------------------------------
             | RELASI TEMPAT KOS (DITAMBAHKAN DI MIGRATION AWAL)
             |---------------------------------------------
             */
            $table->foreignId('tempat_kos_id')
                ->constrained('tempat_kos')
                ->cascadeOnDelete();

            /*
             |---------------------------------------------
             | RELASI UTAMA
             |---------------------------------------------
             */
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('room_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
             |---------------------------------------------
             | PERIODE SEWA
             |---------------------------------------------
             */
            $table->date('start_date');
            $table->date('end_date')->nullable();

            /*
             |---------------------------------------------
             | BIAYA
             |---------------------------------------------
             */
            $table->decimal('deposit_paid', 10, 2)->default(0);
            $table->decimal('monthly_rent', 10, 2);

            /*
             |---------------------------------------------
             | STATUS SEWA
             |---------------------------------------------
             */
            $table->enum('status', [
                'pending',
                'active',
                'checkout_requested',
                'checked_out',
                'completed',
                'expired',
                'cancelled'
            ])->default('pending');

            /*
             |---------------------------------------------
             | CATATAN
             |---------------------------------------------
             */
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();

            /*
             |---------------------------------------------
             | APPROVAL
             |---------------------------------------------
             */
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
             |---------------------------------------------
             | PAYMENT METHOD
             |---------------------------------------------
             */
            $table->string('payment_method')->nullable();
            $table->string('payment_sub_method')->nullable();

            /*
             |---------------------------------------------
             | DOWN PAYMENT (DP)
             |---------------------------------------------
             */
            $table->enum('dp_payment_status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->boolean('dp_paid')->default(false);
            $table->timestamp('dp_verified_at')->nullable();
            $table->foreignId('dp_verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('dp_rejection_reason')->nullable();

            /*
             |---------------------------------------------
             | TIMESTAMP
             |---------------------------------------------
             */
            $table->timestamps();

            /*
             |---------------------------------------------
             | INDEX
             |---------------------------------------------
             */
            $table->index(['tempat_kos_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['room_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
