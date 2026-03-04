<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            $setting = Cache::remember("setting.{$key}", 60, function () use ($key) {
                return static::where('key', $key)->first();
            });

            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.{$key}");
    }

    /**
     * Get multiple settings at once.
     * Returns associative array [key => value].
     */
    public static function getMany(array $keys = []): array
    {
        try {
            $query = static::query();
            if (!empty($keys)) {
                $query->whereIn('key', $keys);
            }
            return $query->pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
}
