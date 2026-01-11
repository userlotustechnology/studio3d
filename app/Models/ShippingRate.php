<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $fillable = [
        'state_code',
        'state_name',
        'rate',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Buscar frete por código do estado
    public static function getRate($stateCode): float
    {
        $rate = static::where('state_code', strtoupper($stateCode))
            ->where('is_active', true)
            ->first();

        return $rate ? $rate->rate : 30.00; // Padrão 30 para estados não cadastrados
    }
}
