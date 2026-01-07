<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing settings
        DB::table('settings')->truncate();

        $settings = [
            // General Settings
            [
                'key' => 'app_name',
                'value' => 'KosSmart',
                'description' => 'Application name',
            ],
            [
                'key' => 'contact_email',
                'value' => 'admin@kossmart.com',
                'description' => 'Contact email',
            ],
            [
                'key' => 'contact_phone',
                'value' => '08123456789',
                'description' => 'Contact phone number',
            ],
            [
                'key' => 'kost_address',
                'value' => 'Jl. Contoh No. 123, Kota',
                'description' => 'Kost address',
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'description' => 'Application timezone',
            ],
            [
                'key' => 'currency',
                'value' => 'IDR',
                'description' => 'Default currency',
            ],

            // Billing Settings
            [
                'key' => 'default_due_date',
                'value' => '5',
                'description' => 'Default billing due date (day of month)',
            ],
            [
                'key' => 'late_fee_type',
                'value' => 'fixed',
                'description' => 'Late fee type: fixed or percentage',
            ],
            [
                'key' => 'late_fee_amount',
                'value' => '50000',
                'description' => 'Late fee amount',
            ],
            [
                'key' => 'grace_period',
                'value' => '3',
                'description' => 'Grace period in days before late fee applies',
            ],
            [
                'key' => 'auto_generate_billing',
                'value' => '1',
                'description' => 'Auto generate monthly billing',
            ],
            [
                'key' => 'payment_methods',
                'value' => '["cash","transfer"]',
                'description' => 'Enabled payment methods',
            ],
            [
                'key' => 'bank_name',
                'value' => 'Bank BCA',
                'description' => 'Bank name for transfer',
            ],
            [
                'key' => 'account_number',
                'value' => '1234567890',
                'description' => 'Bank account number',
            ],
            [
                'key' => 'account_holder',
                'value' => 'KosSmart',
                'description' => 'Bank account holder name',
            ],

            // Notification Settings
            [
                'key' => 'notify_new_booking',
                'value' => '1',
                'description' => 'Send notification for new bookings',
            ],
            [
                'key' => 'notify_payment_received',
                'value' => '1',
                'description' => 'Send notification for payments received',
            ],
            [
                'key' => 'notify_overdue',
                'value' => '1',
                'description' => 'Send notification for overdue bills',
            ],
            [
                'key' => 'reminder_days',
                'value' => '3',
                'description' => 'Days before due date to send reminder',
            ],
            [
                'key' => 'send_reminder_email',
                'value' => '1',
                'description' => 'Send payment reminder via email',
            ],

            // Security Settings
            [
                'key' => 'min_password_length',
                'value' => '8',
                'description' => 'Minimum password length',
            ],
            [
                'key' => 'require_uppercase',
                'value' => '1',
                'description' => 'Require uppercase in password',
            ],
            [
                'key' => 'require_number',
                'value' => '1',
                'description' => 'Require number in password',
            ],
            [
                'key' => 'require_special',
                'value' => '0',
                'description' => 'Require special character in password',
            ],
            [
                'key' => 'session_lifetime',
                'value' => '120',
                'description' => 'Session lifetime in minutes',
            ],
            [
                'key' => 'remember_me_enabled',
                'value' => '1',
                'description' => 'Enable remember me feature',
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'description' => 'Maximum login attempts before lockout',
            ],
            [
                'key' => 'lockout_duration',
                'value' => '15',
                'description' => 'Lockout duration in minutes',
            ],
            [
                'key' => 'require_email_verification',
                'value' => '1',
                'description' => 'Require email verification for new users',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        $this->command->info('Settings seeded successfully!');
    }
}