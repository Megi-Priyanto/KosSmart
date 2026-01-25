<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TempatKos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset tables
        User::query()->delete();
        TempatKos::query()->delete();
        
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('ALTER TABLE tempat_kos AUTO_INCREMENT = 1;');

        // ==============================
        // 1. CREATE SUPER ADMIN
        // ==============================
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@kossmart.com',
            'phone' => '0812-3456-7890',
            'password' => Hash::make('SuperAdmin123!'),
            'role' => 'super_admin',
            'tempat_kos_id' => null,
            'email_verified_at' => now(),
        ]);

        $this->command->info('✓ Super Admin created');

        // ==============================
        // 2. CREATE TEMPAT KOS
        // ==============================
        $kos1 = TempatKos::create([
            'nama_kos' => 'Kos Mawar Indah',
            'alamat' => 'Jl. Mawar No. 123',
            'kota' => 'Bandung',
            'kecamatan' => 'Sukajadi',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40123',
            'telepon' => '022-1234567',
            'email' => 'mawar@kossmart.com',
            'deskripsi' => 'Kos nyaman dengan fasilitas lengkap di pusat kota Bandung',
            'status' => 'active',
            'fasilitas' => ['WiFi', 'Parkir', 'Dapur Bersama', 'Laundry'],
        ]);

        $kos2 = TempatKos::create([
            'nama_kos' => 'Kos Melati Asri',
            'alamat' => 'Jl. Melati No. 456',
            'kota' => 'Jakarta',
            'kecamatan' => 'Menteng',
            'provinsi' => 'DKI Jakarta',
            'kode_pos' => '12345',
            'telepon' => '021-9876543',
            'email' => 'melati@kossmart.com',
            'deskripsi' => 'Kos eksklusif dekat universitas dan pusat bisnis',
            'status' => 'active',
            'fasilitas' => ['WiFi', 'AC', 'Security 24/7', 'Gym'],
        ]);

        $this->command->info('✓ 2 Tempat Kos created');

        // ==============================
        // 3. CREATE ADMIN KOS
        // ==============================
        $adminKos1 = User::create([
            'name' => 'Admin Kos Mawar',
            'email' => 'admin.mawar@kossmart.com',
            'phone' => '0813-1111-1111',
            'password' => Hash::make('AdminMawar123!'),
            'role' => 'admin',
            'tempat_kos_id' => $kos1->id,
            'email_verified_at' => now(),
        ]);

        $adminKos2 = User::create([
            'name' => 'Admin Kos Melati',
            'email' => 'admin.melati@kossmart.com',
            'phone' => '0813-2222-2222',
            'password' => Hash::make('AdminMelati123!'),
            'role' => 'admin',
            'tempat_kos_id' => $kos2->id,
            'email_verified_at' => now(),
        ]);

        $this->command->info('✓ 2 Admin Kos created');

        // ==============================
        // 4. CREATE USERS (PENGHUNI)
        // ==============================
        $users = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'phone' => '0812-1111-1111',
                'tempat_kos_id' => $kos1->id,
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'phone' => '0812-2222-2222',
                'tempat_kos_id' => $kos1->id,
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@example.com',
                'phone' => '0812-3333-3333',
                'tempat_kos_id' => $kos2->id,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'phone' => '0812-4444-4444',
                'tempat_kos_id' => $kos2->id,
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'password' => Hash::make('User123!'),
                'role' => 'user',
                'tempat_kos_id' => $userData['tempat_kos_id'],
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('✓ 4 Users created');

        // ==============================
        // SUMMARY
        // ==============================
        $this->command->info('');
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('  DATABASE SEEDED SUCCESSFULLY');
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('SUPER ADMIN:');
        $this->command->info('  Email    : superadmin@kossmart.com');
        $this->command->info('  Password : SuperAdmin123!');
        $this->command->info('');
        $this->command->info('ADMIN KOS MAWAR:');
        $this->command->info('  Email    : admin.mawar@kossmart.com');
        $this->command->info('  Password : AdminMawar123!');
        $this->command->info('');
        $this->command->info('ADMIN KOS MELATI:');
        $this->command->info('  Email    : admin.melati@kossmart.com');
        $this->command->info('  Password : AdminMelati123!');
        $this->command->info('');
        $this->command->info('REGULAR USERS (4 users):');
        $this->command->info('  Email    : budi@example.com, siti@example.com, dst');
        $this->command->info('  Password : User123!');
        $this->command->info('');
        $this->command->info('TOTAL:');
        $this->command->info('  • 1 Super Admin');
        $this->command->info('  • 2 Tempat Kos (dengan Kecamatan)');
        $this->command->info('  • 2 Admin Kos');
        $this->command->info('  • 4 Users');
        $this->command->info('══════════════════════════════════════════');
    }
}