<?php

use App\Models\Setting;

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
        return Setting::get($key, $default);
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
        return Setting::allSettings();
    }
}

if (!function_exists('update_setting')) {
    /**
     * Update or create a setting
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    function update_setting($key, $value)
    {
        return Setting::set($key, $value);
    }
}

if (!function_exists('has_setting')) {
    /**
     * Check if setting exists
     *
     * @param string $key
     * @return bool
     */
    function has_setting($key)
    {
        return Setting::has($key);
    }
}

if (!function_exists('remove_setting')) {
    /**
     * Remove a setting
     *
     * @param string $key
     * @return bool
     */
    function remove_setting($key)
    {
        return Setting::remove($key);
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

if (!function_exists('app_logo')) {
    /**
     * Get application logo URL
     *
     * @return string
     */
    function app_logo()
    {
        $logo = setting('app_logo');
        return $logo ? asset('storage/images/' . $logo) : asset('images/logo.png');
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