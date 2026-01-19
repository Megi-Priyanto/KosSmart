<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            // Bank Transfer
            [
                'name' => 'BCA',
                'type' => 'bank',
                'account_number' => '1234567890',
                'account_name' => 'PT KosSmart Indonesia',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'BNI',
                'type' => 'bank',
                'account_number' => '0987654321',
                'account_name' => 'PT KosSmart Indonesia',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Mandiri',
                'type' => 'bank',
                'account_number' => '1122334455',
                'account_name' => 'PT KosSmart Indonesia',
                'order' => 3,
                'is_active' => true,
            ],
            
            // E-Wallet
            [
                'name' => 'DANA',
                'type' => 'ewallet',
                'account_number' => '08123456789',
                'account_name' => 'KosSmart',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'OVO',
                'type' => 'ewallet',
                'account_number' => '08123456789',
                'account_name' => 'KosSmart',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'GoPay',
                'type' => 'ewallet',
                'account_number' => '08123456789',
                'account_name' => 'KosSmart',
                'order' => 6,
                'is_active' => true,
            ],
            
            // QRIS
            [
                'name' => 'QRIS',
                'type' => 'qris',
                'account_number' => null,
                'account_name' => 'PT KosSmart Indonesia',
                'instructions' => 'Scan QR Code di bawah ini untuk membayar',
                'qr_code_path' => 'qr_codes/qris-kossmart.png', // ✅ Path QR Code
                'order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['name' => $method['name'], 'type' => $method['type']],
                $method
            );
        }
    }
}