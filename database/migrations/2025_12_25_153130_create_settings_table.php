<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('key');
        });

        // Insert default settings
        DB::table('settings')->insert([
            // General Settings
            [
                'key' => 'app_name',
                'value' => 'KosSmart',
                'description' => 'Application name',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'contact_email',
                'value' => 'admin@kossmart.com',
                'description' => 'Contact email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'contact_phone',
                'value' => '08123456789',
                'description' => 'Contact phone number',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'description' => 'Application timezone',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'currency',
                'value' => 'IDR',
                'description' => 'Default currency',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Billing Settings
            [
                'key' => 'default_due_date',
                'value' => '5',
                'description' => 'Default billing due date (day of month)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'late_fee_type',
                'value' => 'fixed',
                'description' => 'Late fee type: fixed or percentage',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'late_fee_amount',
                'value' => '50000',
                'description' => 'Late fee amount',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'grace_period',
                'value' => '3',
                'description' => 'Grace period in days before late fee applies',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'auto_generate_billing',
                'value' => '1',
                'description' => 'Auto generate monthly billing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'payment_methods',
                'value' => '["cash","transfer"]',
                'description' => 'Enabled payment methods',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'bank_name',
                'value' => 'Bank BCA',
                'description' => 'Bank name for transfer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'account_number',
                'value' => '1234567890',
                'description' => 'Bank account number',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'account_holder',
                'value' => 'KosSmart',
                'description' => 'Bank account holder name',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Notification Settings
            [
                'key' => 'notify_new_booking',
                'value' => '1',
                'description' => 'Send notification for new bookings',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'notify_payment_received',
                'value' => '1',
                'description' => 'Send notification for payments received',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'notify_overdue',
                'value' => '1',
                'description' => 'Send notification for overdue bills',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'reminder_days',
                'value' => '3',
                'description' => 'Days before due date to send reminder',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'send_reminder_email',
                'value' => '1',
                'description' => 'Send payment reminder via email',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Security Settings
            [
                'key' => 'min_password_length',
                'value' => '8',
                'description' => 'Minimum password length',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'require_uppercase',
                'value' => '1',
                'description' => 'Require uppercase in password',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'require_number',
                'value' => '1',
                'description' => 'Require number in password',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'require_special',
                'value' => '0',
                'description' => 'Require special character in password',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'session_lifetime',
                'value' => '120',
                'description' => 'Session lifetime in minutes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'remember_me_enabled',
                'value' => '1',
                'description' => 'Enable remember me feature',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'description' => 'Maximum login attempts before lockout',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'lockout_duration',
                'value' => '15',
                'description' => 'Lockout duration in minutes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'require_email_verification',
                'value' => '1',
                'description' => 'Require email verification for new users',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};