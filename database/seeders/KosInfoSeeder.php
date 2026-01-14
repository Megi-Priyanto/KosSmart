<?php
// database/seeders/KosInfoSeeder.php

namespace Database\Seeders;

use App\Models\Rent;
use App\Models\Room;
use App\Models\KosInfo;
use Illuminate\Database\Seeder;

class KosInfoSeeder extends Seeder
{
    public function run(): void
    {
        Rent::query()->delete();   // rents
        Room::query()->delete();   // rooms
        KosInfo::query()->delete(); // kos_info

        KosInfo::create([
            'name' => 'KosSmart Residence',
            'address' => 'Jalan Sindangsari Indah II No. 55, RT.6/RW.10, Kelurahan Antapani Wetan, Antapani (Cicadas)',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'postal_code' => '40291',
            'phone' => '081324084335',
            'whatsapp' => '081324084335',
            'email' => 'kossmartinaja@gmail.com',
            'description' => 'Kos modern dan nyaman di pusat kota Bandung dengan fasilitas lengkap dan keamanan 24 jam.',
            'general_facilities' => [
                'Parkir Motor & Mobil',
                'WiFi 1000 Mbps',
                'CCTV 24 Jam',
                'Ruang Santai',
                'Toilet L/P',
                'Security',
                'Taman',
                'Cafe'
            ],
            'rules' => [
                'Jam malam maksimal pukul 22.00 WIB',
                'Dilarang membawa tamu menginap',
                'Dilarang membawa binatang peliharaan',
                'Dilarang merokok di dalam kamar',
                'Wajib menjaga kebersihan bersama'
            ],
            'images' => [],
            'checkin_time' => '08:00:00',
            'checkout_time' => '12:00:00',
            'is_active' => false,
        ]);
    }
}
