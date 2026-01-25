<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kos_info', function (Blueprint $table) {
            $table->id();

            // FK ke tempat_kos (SATUAN UTAMA)
            $table->foreignId('tempat_kos_id')
                ->constrained('tempat_kos')
                ->cascadeOnDelete();

            $table->string('name');
            $table->text('address');
            $table->string('city', 100);
            $table->string('province', 100);
            $table->string('postal_code', 10)->nullable();
            $table->string('phone', 20);
            $table->string('whatsapp', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->json('general_facilities')->nullable();
            $table->json('rules')->nullable();
            $table->json('images')->nullable();
            $table->time('checkin_time')->default('14:00:00');
            $table->time('checkout_time')->default('12:00:00');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kos_info');
    }
};
