<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'fee_percentage',
        'fee_fixed',
        'discount_percentage',
        'discount_fixed',
        'settlement_days',
        'is_active',
    ];

    protected $casts = [
        'fee_percentage' => 'decimal:2',
        'fee_fixed' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_fixed' => 'decimal:2',
        'settlement_days' => 'integer',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'payment_method', 'code');
    }

    public function cashBookEntries(): HasMany
    {
        return $this->hasMany(CashBook::class);
    }

    /**
     * Calcula a taxa total para um valor
     */
    public function calculateFee(float $amount): float
    {
        $percentageFee = ($amount * $this->fee_percentage) / 100;
        return $percentageFee + $this->fee_fixed;
    }

    /**
     * Calcula o desconto total para um valor
     */
    public function calculateDiscount(float $amount): float
    {
        $percentageDiscount = ($amount * $this->discount_percentage) / 100;
        return $percentageDiscount + $this->discount_fixed;
    }

    /**
     * Calcula o valor final com desconto aplicado
     */
    public function calculateFinalAmount(float $amount): float
    {
        return $amount - $this->calculateDiscount($amount);
    }

    /**
     * Calcula o valor líquido (descontando as taxas)
     */
    public function calculateNetAmount(float $amount): float
    {
        return $amount - $this->calculateFee($amount);
    }

    /**
     * Calcula o valor líquido considerando desconto e taxas
     * Primeiro aplica o desconto, depois calcula a taxa sobre o valor com desconto
     */
    public function calculateNetAmountWithDiscount(float $amount): float
    {
        $amountAfterDiscount = $this->calculateFinalAmount($amount);
        return $amountAfterDiscount - $this->calculateFee($amountAfterDiscount);
    }

    /**
     * Calcula a data de liquidação baseada nos dias de compensação
     */
    public function calculateSettlementDate(\Carbon\Carbon $transactionDate = null): \Carbon\Carbon
    {
        $date = $transactionDate ?: now();
        return $date->copy()->addDays($this->settlement_days);
    }

    /**
     * Scope para formas de pagamento ativas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
