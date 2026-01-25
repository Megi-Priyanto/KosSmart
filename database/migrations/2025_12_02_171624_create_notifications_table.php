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

            /*
             |---------------------------------------------
             | RELASI TEMPAT KOS (DITAMBAHKAN)
             |---------------------------------------------
             */
            $table->foreignId('tempat_kos_id')
                ->nullable()
                ->constrained('tempat_kos')
                ->cascadeOnDelete();

            /*
             |---------------------------------------------
             | DATA NOTIFIKASI (KODE ASLI KAMU)
             |---------------------------------------------
             */
            $table->string('type')->default('general');
            $table->string('title')->nullable();
            $table->text('message')->nullable();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // 🔹 billing USER (penyewa kos)
            $table->foreignId('billing_id')
                ->nullable()
                ->constrained('billings')
                ->nullOnDelete();

            // 🔹 billing ADMIN (superadmin → admin)
            $table->foreignId('admin_billing_id')
                ->nullable()
                ->constrained('admin_billings')
                ->nullOnDelete();

            $table->foreignId('rent_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('room_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->date('due_date')->nullable();
            $table->string('status', 20)->default('unread');

            $table->timestamps();

            /*
             |---------------------------------------------
             | INDEX
             |---------------------------------------------
             */
            $table->index('tempat_kos_id');
            $table->index(['user_id', 'status']);
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
