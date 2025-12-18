<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    protected function value(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return match ($this->type) {
                    'boolean' => (bool) $value,
                    'integer' => (int) $value,
                    'json' => json_decode($value, true),
                    default => $value,
                };
            },
            set: function ($value) {
                return match ($this->type) {
                    'boolean' => (string) (int) $value,
                    'integer' => (string) $value,
                    'json' => json_encode($value),
                    default => (string) $value,
                };
            }
        );
    }

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value, string $type = 'string', string $description = null)
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
            ]
        );
    }
}
