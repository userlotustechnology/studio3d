<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingCompany extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'website',
        'tracking_url_template',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Gera a URL de rastreamento com o código fornecido
     */
    public function getTrackingUrl(string $trackingCode): ?string
    {
        if (!$this->tracking_url_template) {
            return null;
        }

        return str_replace('{code}', $trackingCode, $this->tracking_url_template);
    }
}
