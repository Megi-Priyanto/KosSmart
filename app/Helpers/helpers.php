<?php

use App\Models\AppSetting;

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return AppSetting::get($key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * Get all settings as array
     *
     * @return array
     */
    function settings()
    {
        return AppSetting::getAllSettings();
    }
}

if (!function_exists('app_name')) {
    /**
     * Get application name
     *
     * @return string
     */
    function app_name()
    {
        return setting('app_name', config('app.name', 'KosSmart'));
    }
}

if (!function_exists('image_setting')) {
    function image_setting(string $key, string $default)
    {
        $path = setting($key);

        if ($path) {
            return asset('storage/' . ltrim($path, '/'));
        }

        return asset($default);
    }
}

function carousel_tenant($index)
{
    return image_setting("carousel_tenant_{$index}", 'images/Carousel Tenant.png');
}

function hero_image_empty()
{
    return image_setting('hero_image_empty', 'images/image1.png');
}

if (!function_exists('app_logo')) {
    /**
     * Get application logo URL
     *
     * @return string
     */
    function app_logo()
    {
        return image_setting('app_logo', 'images/logo.png');
    }
}

if (!function_exists('contact_email')) {
    /**
     * Get contact email
     *
     * @return string
     */
    function contact_email()
    {
        return setting('contact_email', 'admin@kossmart.com');
    }
}

if (!function_exists('contact_phone')) {
    /**
     * Get contact phone
     *
     * @return string
     */
    function contact_phone()
    {
        return setting('contact_phone', '08123456789');
    }
}

if (!function_exists('payment_methods')) {
    /**
     * Get enabled payment methods
     *
     * @return array
     */
    function payment_methods()
    {
        $methods = setting('payment_methods', '["cash","transfer"]');
        return json_decode($methods, true) ?? [];
    }
}

if (!function_exists('bank_details')) {
    /**
     * Get bank account details
     *
     * @return array
     */
    function bank_details()
    {
        return [
            'bank_name' => setting('bank_name', 'Bank BCA'),
            'account_number' => setting('account_number', '1234567890'),
            'account_holder' => setting('account_holder', 'KosSmart'),
        ];
    }
}

if (!function_exists('late_fee_amount')) {
    /**
     * Calculate late fee amount
     *
     * @param float $billAmount
     * @return float
     */
    function late_fee_amount($billAmount = 0)
    {
        $type = setting('late_fee_type', 'fixed');
        $amount = setting('late_fee_amount', 50000);

        if ($type === 'percentage') {
            return ($billAmount * $amount) / 100;
        }

        return $amount;
    }
}

if (!function_exists('grace_period_days')) {
    /**
     * Get grace period in days
     *
     * @return int
     */
    function grace_period_days()
    {
        return (int) setting('grace_period', 3);
    }
}

if (!function_exists('default_due_date')) {
    /**
     * Get default billing due date
     *
     * @return int
     */
    function default_due_date()
    {
        return (int) setting('default_due_date', 5);
    }
}

if (!function_exists('is_overdue')) {
    /**
     * Check if a date is overdue considering grace period
     *
     * @param string|Carbon $dueDate
     * @return bool
     */
    function is_overdue($dueDate)
    {
        $gracePeriod = grace_period_days();
        $dueDate = \Carbon\Carbon::parse($dueDate);
        $gracedDate = $dueDate->addDays($gracePeriod);

        return now()->gt($gracedDate);
    }
}

if (!function_exists('carousel_image')) {
    function carousel_image($index)
    {
        return carousel_tenant($index);
    }
}

function carousel_image($index)
{
    return image_setting("carousel_tenant_{$index}", 'images/Carousel Tenant.png');
}

if (!function_exists('tenant_dashboard_image')) {
    function tenant_dashboard_image()
    {
        return image_setting(
            'tenant_dashboard_image',
            'images/Carousel Tenant.png'
        );
    }
}