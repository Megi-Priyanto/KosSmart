<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tempat_kos_id')->constrained('tempat_kos')->onDelete('cascade');
            $table->foreignId('rent_id')->constrained()->onDelete('cascade');
            $table->foreignId('billing_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned(); // 1-5
            $table->text('komentar')->nullable();
            $table->boolean('is_visible')->default(true); // bisa disembunyikan admin
            $table->timestamps();

            // 1 user hanya bisa review 1x per rent
            $table->unique(['user_id', 'rent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
