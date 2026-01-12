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
        'changed_by',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Traduz o status para português
     */
    public static function translateStatus(string $status): string
    {
        return match($status) {
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
            default => $status,
        };
    }

    /**
     * Retorna um ícone para o status
     */
    public static function statusIcon(string $status): string
    {
        return match($status) {
            'pending' => '⏳',
            'processing' => '⚙️',
            'shipped' => '🚚',
            'delivered' => '✓',
            'cancelled' => '✕',
            default => '•',
        };
    }

    /**
     * Retorna a cor para o status
     */
    public static function statusColor(string $status): string
    {
        return match($status) {
            'pending' => '#f59e0b',
            'processing' => '#3b82f6',
            'shipped' => '#0f79f3',
            'delivered' => '#10b981',
            'cancelled' => '#ef4444',
            default => '#6b7280',
        };
    }
}
