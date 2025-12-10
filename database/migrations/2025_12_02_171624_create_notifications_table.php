<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Notifikasi Tagihan Jatuh Tempo');
            $table->date('notification_date');
            $table->enum('status', ['pending', 'processed'])->default('pending');
            $table->timestamps();
        });
    
        Schema::create('notification_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained()->onDelete('cascade');
            $table->foreignId('rent_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->date('due_date'); 
            $table->enum('status', ['pending', 'read'])->default('pending');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // urutan harus DIBALIK
        Schema::dropIfExists('notification_items');
        Schema::dropIfExists('notifications');
    }
};