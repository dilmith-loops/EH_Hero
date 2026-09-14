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
    ];

    /**
     * Get a setting value by key with optional default.
     */
    public static function get(string $key, $default = null)
    {
        try {
            return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
                $setting = static::where('key', $key)->first();
                return $setting ? $setting->value : $default;
            });
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /**
     * Set/update a setting value by key.
     */
    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value]
        );

        Cache::forget("setting.{$key}");
    }

    /**
     * Check if IP rate limiting is enabled.
     */
    public static function isLimitEnabled(): bool
    {
        return static::get('ip_limit_enabled', '0') === '1';
    }

    /**
     * Get maximum allowed generations per IP.
     */
    public static function getMaxGenerationsPerIp(): int
    {
        return (int) static::get('ip_limit_max', 3);
    }

    /**
     * Get limitation period ('lifetime' or 'daily').
     */
    public static function getLimitPeriod(): string
    {
        return static::get('ip_limit_period', 'lifetime');
    }

    /**
     * Custom message to show when limit is reached.
     */
    public static function getLimitMessage(): string
    {
        return static::get(
            'ip_limit_message',
            'You have reached the maximum allowed image generations for this device.'
        );
    }

    /**
     * Check if Platform Maintenance Mode is enabled.
     */
    public static function isMaintenanceEnabled(): bool
    {
        return static::get('maintenance_mode', '0') === '1';
    }

    /**
     * Get custom maintenance mode message.
     */
    public static function getMaintenanceMessage(): string
    {
        return static::get(
            'maintenance_message',
            'Our servers are taking a frosty breather to serve up faster, sharper, and even cooler anime transformations. We will be back online shortly!'
        );
    }
}
