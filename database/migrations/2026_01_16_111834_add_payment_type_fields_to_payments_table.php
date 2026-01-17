<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Tambah kolom payment_type dan payment_sub_method
            $table->string('payment_type')->nullable()->after('payment_method');
            $table->string('payment_sub_method')->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'payment_sub_method']);
        });
    }
};