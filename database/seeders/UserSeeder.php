<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua user demo jika sudah ada
        User::whereIn('email', [
            'admin@kossmart.com',
            'user@kossmart.com',
            'john@kossmart.com',
            'jane@kossmart.com',
            'bob@kossmart.com',
        ])->delete();

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
            'name' => 'John Doe',
            'email' => 'john@kossmart.com',
            'phone' => '081234567892',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // User Biasa 2
        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@kossmart.com',
            'phone' => '081234567893',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // User Biasa 3
        User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@kossmart.com',
            'phone' => '081234567894',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // User Biasa 4 (Belum Verifikasi Email)
        User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@kossmart.com',
            'phone' => '081234567895',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => null, // Belum verifikasi
        ]);

        // User Biasa 5 (Tanpa Telepon)
        User::create([
            'name' => 'Charlie Brown',
            'email' => 'charlie@kossmart.com',
            'phone' => null,
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // User untuk Testing
        User::create([
            'name' => 'Test User',
            'email' => 'test@kossmart.com',
            'phone' => '081234567896',
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
        $this->command->info('  Email: john@kossmart.com');
        $this->command->info('  Password: password123');
        $this->command->info('-----------------------------------');
        $this->command->info('');
        $this->command->info(' Total: 8 users (2 admin, 6 user)');
    }
}