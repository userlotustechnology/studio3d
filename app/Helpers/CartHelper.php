<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use App\Models\Order;

class CartHelper
{
    /**
     * Obter quantidade total de itens no carrinho
     */
    public static function getCartCount(): int
    {
        $draftOrderId = Session::get('draft_order_id');
        
        if (!$draftOrderId) {
            return 0;
        }

        $order = Order::find($draftOrderId);
        
        if (!$order) {
            return 0;
        }

        return $order->items()->sum('quantity');
    }

    /**
     * Obter dados do carrinho
     */
    public static function getCartData(): array
    {
        $draftOrderId = Session::get('draft_order_id');
        
        if (!$draftOrderId) {
            return [
                'count' => 0,
                'items' => [],
                'subtotal' => 0,
                'shipping_cost' => 0,
                'total' => 0,
            ];
        }

        $order = Order::find($draftOrderId);
        
        if (!$order) {
            return [
                'count' => 0,
                'items' => [],
                'subtotal' => 0,
                'shipping_cost' => 0,
                'total' => 0,
            ];
        }

        return [
            'count' => $order->items()->sum('quantity'),
            'items' => $order->items,
            'subtotal' => $order->subtotal,
            'shipping_cost' => $order->shipping_cost,
            'total' => $order->total,
        ];
    }
}
