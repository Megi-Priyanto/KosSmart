<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('app_settings')->truncate();

        $now = now();

        DB::table('app_settings')->insert([
            [
                'key' => 'app_name',
                'type' => 'text',
                'value' => 'KosSmart',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'app_logo',
                'type' => 'image',
                'value' => 'images/logo.png',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'tenant_dashboard_image',
                'type' => 'image',
                'value' => 'images/hero-tenant.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'hero_image_empty',
                'type' => 'image',
                'value' => 'images/hero-empty.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}