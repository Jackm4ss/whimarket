<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PlatformSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'value',
        'type',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (PlatformSetting $setting) {
            Cache::forget("platform_setting_{$setting->key}");
            Cache::forget("platform_setting_active_{$setting->key}");
        });

        static::deleted(function (PlatformSetting $setting) {
            Cache::forget("platform_setting_{$setting->key}");
            Cache::forget("platform_setting_active_{$setting->key}");
        });
    }

    /**
     * Get a setting value by key with caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("platform_setting_{$key}", now()->addHours(24), function () use ($key, $default) {
            $setting = static::where('key', $key)->first();

            if (! $setting) {
                return $default;
            }

            return $setting->is_active ? $setting->value : 0;
        });
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value, ?string $name = null): self
    {
        $setting = static::firstOrNew(['key' => $key]);

        if ($name) {
            $setting->name = $name;
        }

        $setting->value = (string) $value;
        $setting->save();

        Cache::forget("platform_setting_{$key}");

        return $setting;
    }

    /**
     * Get the current active admin fee (nominal rupiah).
     * Returns 0.0 if disabled or not configured.
     */
    public static function getAdminFee(): float
    {
        return (float) static::get('admin_fee', 2000);
    }

    /**
     * Check if the admin fee is currently active.
     */
    public static function isAdminFeeActive(): bool
    {
        return (bool) Cache::remember('platform_setting_active_admin_fee', now()->addHours(24), function () {
            $setting = static::where('key', 'admin_fee')->first();

            return $setting ? (bool) $setting->is_active : true;
        });
    }
}
