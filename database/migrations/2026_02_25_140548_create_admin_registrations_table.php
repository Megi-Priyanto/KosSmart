<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_registrations', function (Blueprint $table) {
            $table->id();

            // ── Data Diri ──────────────────────────────
            $table->string('nama_lengkap');
            $table->string('nik', 16)->unique();
            $table->string('email')->unique();
            $table->string('no_hp', 20);
            $table->string('password'); // hashed sejak awal

            // ── Data Kos ───────────────────────────────
            $table->string('nama_kos');
            $table->text('alamat');
            $table->string('kecamatan', 100);
            $table->string('kota', 100);
            $table->string('provinsi', 100);
            $table->string('kode_pos', 10)->nullable();
            $table->string('telepon_kos', 20)->nullable();
            $table->string('email_kos', 100)->nullable();

            // ── Dokumen ────────────────────────────────
            $table->string('ktp_foto');               // wajib
            $table->string('selfie_ktp');             // wajib
            $table->enum('tipe_kepemilikan', ['pemilik', 'penyewa']);
            $table->string('bukti_kepemilikan');      // sertifikat atau perjanjian sewa
            $table->string('npwp')->nullable();       // opsional

            // ── Status & Review ────────────────────────
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();      // alasan approve/reject dari superadmin
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_registrations');
    }
};