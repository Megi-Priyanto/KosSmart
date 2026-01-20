<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value
     */
    public static function set(string $key, $value, string $type = 'text')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );

        // Clear cache
        Cache::forget("app_setting_{$key}");

        return $setting;
    }

    /**
     * Get all settings as array
     * RENAMED: all() → getAllSettings()
     */
    public static function getAllSettings(): array
    {
        return Cache::remember('all_app_settings', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::forget('all_app_settings');
        
        // Clear individual caches
        $keys = self::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("app_setting_{$key}");
        }
    }

    /**
     * Delete old image when updating
     */
    public function deleteOldImage()
    {
        if ($this->type === 'image' && $this->value) {
            // Don't delete default images
            if (str_starts_with($this->value, 'images/')) {
                return;
            }
            
            if (Storage::disk('public')->exists($this->value)) {
                Storage::disk('public')->delete($this->value);
            }
        }
    }
}