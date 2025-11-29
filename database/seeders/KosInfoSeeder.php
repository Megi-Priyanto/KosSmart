<?php
// database/seeders/KosInfoSeeder.php

namespace Database\Seeders;

use App\Models\KosInfo;
use Illuminate\Database\Seeder;

class KosInfoSeeder extends Seeder
{
    public function run(): void
    {
        KosInfo::create([
            'name' => 'KosSmart Residence',
            'address' => 'Jl. Merdeka No. 123, Kelurahan Suka Maju',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40123',
            'phone' => '022-1234567',
            'whatsapp' => '081234567890',
            'email' => 'info@kossmart.com',
            'description' => 'Kos modern dan nyaman di pusat kota Bandung dengan fasilitas lengkap dan keamanan 24 jam.',
            'general_facilities' => json_encode([
                'Parkir Motor & Mobil',
                'WiFi 100 Mbps',
                'CCTV 24 Jam',
                'Security',
                'Dapur Bersama',
                'Ruang Tamu',
                'Laundry',
                'Air PDAM'
            ]),
            'rules' => json_encode([
                'Jam malam maksimal pukul 22.00 WIB',
                'Dilarang membawa tamu menginap',
                'Dilarang membawa binatang peliharaan',
                'Dilarang merokok di dalam kamar',
                'Wajib menjaga kebersihan bersama'
            ]),
            'images' => json_encode([]),
            'checkin_time' => '14:00:00',
            'checkout_time' => '12:00:00',
            'is_active' => true,
        ]);

        $this->command->info('Data kos info berhasil dibuat!');
    }
}