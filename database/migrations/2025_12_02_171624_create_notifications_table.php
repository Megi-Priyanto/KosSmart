<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Jenis notifikasi: booking, overdue, pembayaran, dsb
            $table->string('type')->default('general'); // ← DITAMBAHKAN DEFAULT

            // Judul notifikasi
            $table->string('title')->nullable();

            // Isi pesan
            $table->text('message')->nullable();

            // User yang menerima notifikasi
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Optional relasi ke rent
            $table->foreignId('rent_id')->nullable()->constrained()->onDelete('set null');

            // Optional relasi ke room
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');

            // Optional tanggal jatuh tempo
            $table->date('due_date')->nullable();

            // Status notifikasi
            $table->enum('status', ['pending', 'read'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
