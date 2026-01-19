<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // BCA, BNI, Mandiri, DANA, OVO, GoPay, QRIS
            $table->string('type'); // bank, ewallet, qris
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->text('instructions')->nullable();
            $table->string('qr_code_path')->nullable(); // ✅ Path untuk QR Code QRIS
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // Tambah kolom payment_method ke admin_billings
        Schema::table('admin_billings', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('admin_billings', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
        
        Schema::dropIfExists('payment_methods');
    }
};