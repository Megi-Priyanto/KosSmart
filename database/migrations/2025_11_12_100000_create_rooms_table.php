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

            // Relasi kos
            $table->foreignId('kos_info_id')->nullable()->constrained('kos_info')->nullOnDelete();

            // Data kamar
            $table->string('room_number')->unique();
            $table->string('floor');
            $table->string('type'); // putra/putri/campur
            $table->integer('capacity')->default(1);

            // Fasilitas fisik
            $table->decimal('size', 8, 2)->nullable();
            $table->boolean('has_window')->default(true);

            // Harga + fasilitas
            $table->decimal('price', 10, 2);
            $table->json('facilities')->nullable();
            $table->json('images')->nullable();

            // Deskripsi dan catatan
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            // Status & perawatan
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->date('last_maintenance')->nullable();

            // Tracking
            $table->integer('view_count')->default(0);

            $table->timestamps();

            // Index
            $table->index(['status', 'type']);
            $table->index('floor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
