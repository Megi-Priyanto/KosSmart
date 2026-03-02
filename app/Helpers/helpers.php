<?php

use App\Models\AppSetting;

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     */
    function setting($key, $default = null)
    {
        return AppSetting::get($key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * Get all settings as array
     */
    function settings()
    {
        return AppSetting::getAllSettings();
    }
}

if (!function_exists('app_name')) {
    /**
     * Get application name
     */
    function app_name()
    {
        return setting('app_name', config('app.name', 'KosSmart'));
    }
}

if (!function_exists('image_setting')) {
    /**
     * Get image URL from settings.
     * - Jika nilai null/kosong → gunakan $default dari public/
     * - Jika nilai diawali 'settings/' → file upload, pakai storage/
     * - Jika nilai lainnya (misal 'images/logo.png') → file default di public/
     */
    function image_setting(string $key, string $default): string
    {
        $path = setting($key);

        if (!$path) {
            return asset($default);
        }

        if (str_starts_with($path, 'settings/')) {
            return asset('storage/' . $path);
        }

        return asset($path);
    }
}

if (!function_exists('app_logo')) {
    /**
     * Get application logo URL
     */
    function app_logo(): string
    {
        return image_setting('app_logo', 'images/logo.png');
    }
}

if (!function_exists('tenant_dashboard_image')) {
    /**
     * Get tenant dashboard hero image URL
     */
    function tenant_dashboard_image(): string
    {
        return image_setting('tenant_dashboard_image', 'images/Carousel Tenant.png');
    }
}

if (!function_exists('hero_image_empty')) {
    /**
     * Get hero image for user without room
     */
    function hero_image_empty(): string
    {
        return image_setting('hero_image_empty', 'images/image1.png');
    }
}

if (!function_exists('carousel_tenant')) {
    function carousel_tenant($index): string
    {
        return image_setting("carousel_tenant_{$index}", 'images/Carousel Tenant.png');
    }
}

if (!function_exists('carousel_image')) {
    function carousel_image($index): string
    {
        return carousel_tenant($index);
    }
}

if (!function_exists('contact_email')) {
    function contact_email(): string
    {
        return setting('contact_email', 'admin@kossmart.com');
    }
}

if (!function_exists('contact_phone')) {
    function contact_phone(): string
    {
        return setting('contact_phone', '08123456789');
    }
}

if (!function_exists('payment_methods')) {
    function payment_methods(): array
    {
        $methods = setting('payment_methods', '["cash","transfer"]');
        return json_decode($methods, true) ?? [];
    }
}

if (!function_exists('bank_details')) {
    function bank_details(): array
    {
        return [
            'bank_name'      => setting('bank_name', 'Bank BCA'),
            'account_number' => setting('account_number', '1234567890'),
            'account_holder' => setting('account_holder', 'KosSmart'),
        ];
    }
}

if (!function_exists('late_fee_amount')) {
    function late_fee_amount($billAmount = 0)
    {
        $type   = setting('late_fee_type', 'fixed');
        $amount = setting('late_fee_amount', 50000);

        if ($type === 'percentage') {
            return ($billAmount * $amount) / 100;
        }

        return $amount;
    }
}

if (!function_exists('grace_period_days')) {
    function grace_period_days(): int
    {
        return (int) setting('grace_period', 3);
    }
}

if (!function_exists('default_due_date')) {
    function default_due_date(): int
    {
        return (int) setting('default_due_date', 5);
    }
}

if (!function_exists('is_overdue')) {
    function is_overdue($dueDate): bool
    {
        $gracePeriod = grace_period_days();
        $dueDate     = \Carbon\Carbon::parse($dueDate);
        $gracedDate  = $dueDate->addDays($gracePeriod);

        return now()->gt($gracedDate);
    }
}