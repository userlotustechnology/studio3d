<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'uuid',
        'access_token',
        'customer_id',
        'billing_address_id',
        'shipping_address_id',
        'subtotal',
        'shipping_cost',
        'carrier_shipping_cost',
        'tracking_code',
        'shipping_company_id',
        'discount',
        'total',
        'status',
        'is_draft',
        'payment_method',
        'payment_id',
        'notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'carrier_shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'is_draft' => 'boolean',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingCompany(): BelongsTo
    {
        return $this->belongsTo(ShippingCompany::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function generateOrderNumber(): string
    {
        return 'PED-' . date('Y') . '-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Gera um token de acesso único e seguro para o pedido
     */
    public function generateAccessToken(): string
    {
        return hash('sha256', $this->id . $this->order_number . uniqid() . time());
    }

    /**
     * Retorna a URL de rastreamento se existir
     */
    public function getTrackingUrl(): ?string
    {
        if (!$this->tracking_code || !$this->shippingCompany) {
            return null;
        }

        return $this->shippingCompany->getTrackingUrl($this->tracking_code);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });

        static::retrieved(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
                $model->save();
            }
        });

        // Processar mudanças de status
        static::updated(function ($order) {
            // Se o pedido saiu de draft para outro status (finalizou)
            if ($order->isDirty('status') && $order->getOriginal('status') === 'draft') {
                $order->convertCartReservationsToSales();
            }
        });
    }

    /**
     * Converte as reservas do carrinho em vendas finais
     */
    public function convertCartReservationsToSales()
    {
        foreach ($this->items as $item) {
            // Registrar a venda final (sem alterar estoque, pois já foi reservado)
            \App\Models\StockMovement::create([
                'product_id' => $item->product_id,
                'order_id' => $this->id,
                'type' => 'sale',
                'quantity' => -$item->quantity,
                'stock_before' => Product::find($item->product_id)->stock,
                'stock_after' => Product::find($item->product_id)->stock, // Sem alteração
                'reason' => 'Venda finalizada - pedido ' . $this->order_number,
                'user_name' => 'Sistema',
            ]);
        }
    }
}
