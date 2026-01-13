<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatusHistory extends Model
{
    protected $fillable = [
        'order_id',
        'from_status',
        'to_status',
        'reason',
        'user_id',
        'changed_by_legacy',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Traduz o status para português
     */
    public static function translateStatus(string $status): string
    {
        return match($status) {
            'draft' => 'Rascunho',
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
            'refunded' => 'Estornado',
            default => $status,
        };
    }

    /**
     * Retorna um ícone para o status
     */
    public static function statusIcon(string $status): string
    {
        return match($status) {
            'draft' => '📋',
            'pending' => '⏳',
            'processing' => '⚙️',
            'shipped' => '🚚',
            'delivered' => '✓',
            'cancelled' => '✕',
            'refunded' => '🔄',
            default => '•',
        };
    }

    /**
     * Retorna a cor para o status
     */
    public static function statusColor(string $status): string
    {
        return match($status) {
            'draft' => '#9ca3af',
            'pending' => '#f59e0b',
            'processing' => '#3b82f6',
            'shipped' => '#0f79f3',
            'delivered' => '#10b981',
            'cancelled' => '#ef4444',
            'refunded' => '#f97316',
            default => '#6b7280',
        };
    }
}
