<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel app_settings
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Key unik untuk setting
            $table->text('value')->nullable(); // Value bisa null
            $table->string('type')->default('text'); // Type: text/image/file
            $table->timestamps();
        });

        // Insert data default ← INI PENTING!
        DB::table('app_settings')->insert([
            ['key' => 'app_name', 'value' => 'KosSmart', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_logo', 'value' => 'images/logo.png', 'type' => 'image', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'tenant_dashboard_image', 'value' => 'images/Carousel Tenant.png', 'type' => 'image'],
            ['key' => 'hero_image_empty', 'value' => 'images/image1.png', 'type' => 'image', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};