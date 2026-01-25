<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
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
             | RELASI KOS INFO
             |---------------------------------------------
             */
            $table->foreignId('kos_info_id')
                ->nullable()
                ->constrained('kos_info')
                ->nullOnDelete();

            // Data kamar
            $table->string('room_number')->unique();
            $table->string('floor');
            $table->string('type'); // putra / putri / campur
            $table->integer('capacity')->default(1);

            // Jenis sewa
            $table->enum('jenis_sewa', ['bulan', 'tahun'])->default('bulan');

            // Fasilitas fisik
            $table->decimal('size', 8, 2)->nullable();
            $table->boolean('has_window')->default(true);

            /*
             |---------------------------------------------
             | SISTEM SEWA
             |---------------------------------------------
             */
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly');
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('yearly_price', 10, 2)->nullable();

            // Fasilitas & media
            $table->json('facilities')->nullable();
            $table->json('images')->nullable();

            // Deskripsi
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            // Status kamar
            $table->enum('status', ['available', 'occupied', 'maintenance'])
                ->default('available');

            $table->date('last_maintenance')->nullable();

            // Tracking
            $table->integer('view_count')->default(0);

            /*
             |---------------------------------------------
             | FITUR NOTIFIKASI
             |---------------------------------------------
             */
            $table->string('notification_title')
                ->default('Notifikasi Tagihan Jatuh Tempo');

            $table->date('notification_date')->nullable();

            $table->enum('notification_status', ['pending', 'sent'])
                ->default('pending');

            $table->timestamps();

            // Index
            $table->index(['tempat_kos_id', 'status', 'type']);
            $table->index('floor');
            $table->index('notification_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
