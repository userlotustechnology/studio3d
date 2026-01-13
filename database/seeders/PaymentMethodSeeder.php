<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'PIX',
                'code' => 'pix',
                'description' => 'Pagamento instantâneo via PIX',
                'fee_percentage' => 0.99,
                'fee_fixed' => 0.00,
                'discount_percentage' => 5.00, // 5% de desconto no PIX
                'discount_fixed' => 0.00,
                'settlement_days' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Cartão de Crédito',
                'code' => 'credit_card',
                'description' => 'Pagamento via cartão de crédito',
                'fee_percentage' => 3.99,
                'fee_fixed' => 0.39,
                'discount_percentage' => 0.00,
                'discount_fixed' => 0.00,
                'settlement_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Cartão de Débito',
                'code' => 'debit_card',
                'description' => 'Pagamento via cartão de débito',
                'fee_percentage' => 1.99,
                'fee_fixed' => 0.00,
                'discount_percentage' => 2.00, // 2% de desconto no débito
                'discount_fixed' => 0.00,
                'settlement_days' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Boleto Bancário',
                'code' => 'boleto',
                'description' => 'Pagamento via boleto bancário',
                'fee_percentage' => 0.00,
                'fee_fixed' => 3.49,
                'discount_percentage' => 3.00, // 3% de desconto no boleto
                'discount_fixed' => 0.00,
                'settlement_days' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Transferência/TED',
                'code' => 'bank_transfer',
                'description' => 'Pagamento via transferência bancária',
                'fee_percentage' => 0.00,
                'fee_fixed' => 0.00,
                'discount_percentage' => 3.00, // 3% de desconto na transferência
                'discount_fixed' => 0.00,
                'settlement_days' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Dinheiro',
                'code' => 'cash',
                'description' => 'Pagamento em dinheiro (balcão)',
                'fee_percentage' => 0.00,
                'fee_fixed' => 0.00,
                'discount_percentage' => 10.00, // 10% de desconto à vista
                'discount_fixed' => 0.00,
                'settlement_days' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }
}
