<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('app_settings');
        });

        static::deleted(function () {
            Cache::forget('app_settings');
        });
    }

    public static function get($key, $default = null)
    {
        $settings = Cache::remember('app_settings', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    public static function set($key, $value)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget('app_settings');

        return $setting ? true : false;
    }

    public static function has($key)
    {
        return static::where('key', $key)->exists();
    }

    public static function remove($key)
    {
        Cache::forget('app_settings');
        return static::where('key', $key)->delete();
    }

    public static function allSettings()
    {
        return Cache::remember('app_settings', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }
}