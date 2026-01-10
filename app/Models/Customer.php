<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'cpf',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function billingAddresses()
    {
        return $this->hasMany(Address::class)->where('type', 'residential');
    }

    public function shippingAddresses()
    {
        return $this->hasMany(Address::class)->where('type', 'shipping');
    }
}
