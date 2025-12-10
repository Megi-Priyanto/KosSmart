<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua user
        User::query()->delete();

        // Reset auto increment users
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');

        // Admin Utama
        User::create([
            'name' => 'Admin KosSmart',
            'email' => 'admin@kossmart.com',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Admin Kedua (untuk testing multiple admin)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@kossmart.com',
            'phone' => '081234567891',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // User Biasa 1
        User::create([
            'name' => 'Septri',
            'email' => 'septri@kossmart.com',
            'phone' => '081234567892',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // User Biasa 2
        User::create([
            'name' => 'Megi OXZ',
            'email' => 'megi@kossmart.com',
            'phone' => '081234567893',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $this->command->info(' User demo berhasil dibuat!');
        $this->command->info('');
        $this->command->info(' Kredensial Login:');
        $this->command->info('-----------------------------------');
        $this->command->info('Admin:');
        $this->command->info('  Email: admin@kossmart.com');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('User:');
        $this->command->info('  Email: megi@kossmart.com');
        $this->command->info('  Password: password123');
        $this->command->info('-----------------------------------');
        $this->command->info('');
        $this->command->info(' Total: 4 users (2 admin, 2 user)');
    }
}
