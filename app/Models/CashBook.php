<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CashBook extends Model
{
    protected $table = 'cash_book';

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'type',
        'category',
        'amount',
        'fee_amount',
        'net_amount',
        'description',
        'metadata',
        'transaction_date',
        'settlement_date',
        'reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'metadata' => 'array',
        'transaction_date' => 'datetime',
        'settlement_date' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    /**
     * Traduz a categoria para português
     */
    public static function translateCategory(string $category): string
    {
        $translations = [
            'sale' => 'Venda',
            'payment_fee' => 'Taxa de Pagamento',
            'shipping_revenue' => 'Frete Recebido',
            'shipping_cost' => 'Custo de Frete',
            'product_cost' => 'Custo de Produtos (CMV)',
            'refund' => 'Estorno',
            'expense' => 'Despesa',
            'revenue' => 'Receita',
            'withdrawal' => 'Retirada',
            'investment' => 'Investimento',
            'other' => 'Outros',
        ];

        return $translations[$category] ?? ucfirst($category);
    }

    /**
     * Registra uma entrada no livro caixa
     */
    public static function recordEntry(array $data): self
    {
        // Calcula automaticamente o valor líquido se não informado
        if (!isset($data['net_amount'])) {
            $data['net_amount'] = $data['amount'] - ($data['fee_amount'] ?? 0);
        }

        // Define data da transação como hoje se não informada
        if (!isset($data['transaction_date'])) {
            $data['transaction_date'] = now()->format('Y-m-d');
        }

        return self::create($data);
    }

    /**
     * Registra venda de um pedido (apenas subtotal, sem frete)
     * O frete é registrado separadamente em recordShippingRevenue()
     */
    public static function recordSale(Order $order): self
    {
        $paymentMethod = PaymentMethod::where('code', $order->payment_method)->first();
        $feeAmount = 0;
        $settlementDate = null;

        if ($paymentMethod) {
            // Taxa é calculada sobre o subtotal (sem frete)
            $feeAmount = $paymentMethod->calculateFee($order->subtotal);
            $settlementDate = $paymentMethod->calculateSettlementDate();
        }

        return self::recordEntry([
            'order_id' => $order->id,
            'payment_method_id' => $paymentMethod?->id,
            'type' => 'credit',
            'category' => 'sale',
            'amount' => $order->subtotal,
            'fee_amount' => 0, // Taxa será débito separado
            'net_amount' => $order->subtotal,
            'description' => "Venda - Pedido #{$order->order_number}",
            'settlement_date' => $settlementDate,
            'metadata' => [
                'order_number' => $order->order_number,
                'customer_name' => $order->customer->name ?? null,
                'payment_method' => $order->payment_method,
            ],
        ]);
    }

    /**
     * Registra taxa de uma venda
     */
    public static function recordSaleFee(Order $order): ?self
    {
        $paymentMethod = PaymentMethod::where('code', $order->payment_method)->first();
        
        if (!$paymentMethod) {
            return null;
        }

        $feeAmount = $paymentMethod->calculateFee($order->total);
        
        if ($feeAmount <= 0) {
            return null;
        }

        return self::recordEntry([
            'order_id' => $order->id,
            'payment_method_id' => $paymentMethod->id,
            'type' => 'debit',
            'category' => 'payment_fee',
            'amount' => $feeAmount,
            'fee_amount' => $feeAmount,
            'net_amount' => -$feeAmount,
            'description' => "Taxa {$paymentMethod->name} - Pedido #{$order->order_number}",
            'settlement_date' => $paymentMethod->calculateSettlementDate(),
            'metadata' => [
                'order_number' => $order->order_number,
                'payment_method' => $order->payment_method,
                'fee_percentage' => $paymentMethod->fee_percentage,
                'fee_fixed' => $paymentMethod->fee_fixed,
            ],
        ]);
    }

    /**
     * Registra frete recebido
     */
    public static function recordShippingRevenue(Order $order): ?self
    {
        if ($order->shipping_cost <= 0) {
            return null;
        }

        return self::recordEntry([
            'order_id' => $order->id,
            'type' => 'credit',
            'category' => 'shipping_revenue',
            'amount' => $order->shipping_cost,
            'description' => "Frete recebido - Pedido #{$order->order_number}",
            'metadata' => [
                'order_number' => $order->order_number,
                'shipping_cost' => $order->shipping_cost,
                'carrier_cost' => $order->carrier_shipping_cost ?? 0,
            ],
        ]);
    }

    /**
     * Registra custo do frete pago à transportadora
     */
    public static function recordShippingCost(Order $order): ?self
    {
        if (!$order->carrier_shipping_cost || $order->carrier_shipping_cost <= 0) {
            return null;
        }

        return self::recordEntry([
            'order_id' => $order->id,
            'type' => 'debit',
            'category' => 'shipping_cost',
            'amount' => $order->carrier_shipping_cost,
            'description' => "Custo frete - Pedido #{$order->order_number}",
            'metadata' => [
                'order_number' => $order->order_number,
                'carrier_cost' => $order->carrier_shipping_cost,
                'shipping_revenue' => $order->shipping_cost,
                'shipping_company_id' => $order->shipping_company_id,
            ],
        ]);
    }

    /**
     * Registra custo dos produtos vendidos (CMV - Custo de Mercadoria Vendida)
     */
    public static function recordProductCosts(Order $order): ?self
    {
        $totalCost = 0;
        $products = [];

        foreach ($order->items as $item) {
            if ($item->product && $item->product->cost_price > 0) {
                $itemCost = $item->product->cost_price * $item->quantity;
                $totalCost += $itemCost;
                $products[] = [
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->product->cost_price,
                    'total_cost' => $itemCost,
                ];
            }
        }

        if ($totalCost <= 0) {
            return null;
        }

        return self::recordEntry([
            'order_id' => $order->id,
            'type' => 'debit',
            'category' => 'product_cost',
            'amount' => $totalCost,
            'net_amount' => -$totalCost,
            'description' => "CMV (Custo dos Produtos) - Pedido #{$order->order_number}",
            'metadata' => [
                'order_number' => $order->order_number,
                'products' => $products,
                'total_items' => count($products),
            ],
        ]);
    }

    /**
     * Scopes para filtros
     */
    public function scopeCredits($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeDebits($query)
    {
        return $query->where('type', 'debit');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }
}
