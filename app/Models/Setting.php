<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'description', 'type'];

    protected $casts = [
        'value' => 'string',
    ];

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return $setting->getValue();
    }

    public static function set(string $key, $value, string $type = 'string', ?string $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => self::castValue($value, $type),
                'type' => $type,
                'description' => $description
            ]
        );
    }

    public function getValue()
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'array' => json_decode($this->value, true),
            'json' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    private static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) $value,
            'array', 'json' => is_string($value) ? $value : json_encode($value),
            default => (string) $value,
        };
    }
}

