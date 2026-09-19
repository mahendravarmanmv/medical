<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    public static function get(
        string $group,
        string $key,
        mixed $default = null
    ): mixed {
        $setting = static::query()
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => filter_var(
                $setting->value,
                FILTER_VALIDATE_BOOLEAN
            ),
            'decimal' => (float) $setting->value,
            'integer' => (int) $setting->value,
            'json' => json_decode(
                $setting->value,
                true
            ),
            default => $setting->value,
        };
    }
}