<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'order_id',
        'user_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'reason',
        'user_name_legacy',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registra uma movimentação de estoque
     */
    public static function recordMovement(
        int $productId,
        string $type,
        int $quantity,
        ?int $orderId = null,
        string $reason = '',
        ?int $userId = null,
        ?string $userNameLegacy = null
    ): self {
        $product = Product::findOrFail($productId);
        $stockBefore = $product->stock;
        
        // Calcular novo estoque baseado no tipo
        $outTypes = ['out', 'cart_reservation'];
        $inTypes = ['in', 'cart_return'];
        
        if (in_array($type, $outTypes)) {
            $movement = -abs($quantity);
        } elseif (in_array($type, $inTypes)) {
            $movement = abs($quantity);
        } else {
            // Para 'adjustment', usar a quantidade como passada
            $movement = $quantity;
        }
        
        $stockAfter = $stockBefore + $movement;
        
        // Atualizar estoque do produto
        $product->update(['stock' => $stockAfter]);
        
        // Registrar movimentação
        return self::create([
            'product_id' => $productId,
            'order_id' => $orderId,
            'user_id' => $userId,
            'type' => $type,
            'quantity' => $movement,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reason' => $reason,
            'user_name_legacy' => $userNameLegacy,
        ]);
    }
}
