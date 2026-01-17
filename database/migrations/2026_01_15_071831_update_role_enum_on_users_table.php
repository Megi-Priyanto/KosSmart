<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah role menjadi enum dengan super_admin, admin, user
            $table->enum('role', ['super_admin', 'admin', 'user'])
                  ->default('user')
                  ->change();
            
            // Tambah foreign key ke tempat_kos
            $table->foreignId('tempat_kos_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('tempat_kos')
                  ->onDelete('cascade');
            
            // Index untuk performa query
            $table->index(['role', 'tempat_kos_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tempat_kos_id']);
            $table->dropColumn('tempat_kos_id');
            $table->enum('role', ['admin', 'user'])->default('user')->change();
        });
    }
};