<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Retrieve a setting value by key.
     * Caches the value to avoid repeated DB hits.
     */
    public function get($key, $default = null)
    {
        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key.
     * Creates or updates the DB record and clears the cache for that key.
     */
    public function set($key, $value, $type = 'string')
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );

        Cache::forget("settings.{$key}");
    }

    /**
     * Get all settings as an associative array.
     * Caches the entire dataset.
     */
    public function all()
    {
        return Cache::rememberForever('settings.all', function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }
    
    /**
     * Clear all settings cache.
     */
    public function flushCache()
    {
        $keys = Setting::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("settings.{$key}");
        }
        Cache::forget('settings.all');
    }
}
