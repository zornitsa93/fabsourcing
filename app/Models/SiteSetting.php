<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type', 'translatable'];

    protected $casts = ['translatable' => 'boolean'];

    public static function get(string $key, string $locale = null, $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        if ($setting->translatable) {
            $locale = $locale ?? app()->getLocale();
            $decoded = json_decode($setting->value, true);
            return $decoded[$locale] ?? $decoded[array_key_first($decoded)] ?? $default;
        }

        return $setting->value ?? $default;
    }
}
