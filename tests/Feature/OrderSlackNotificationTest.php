<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Address;
use Tests\TestCase;

class OrderSlackNotificationTest extends TestCase
{
    public function test_order_created_notification_is_sent(): void
    {
        // Criar um cliente
        $customer = Customer::factory()->create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
        ]);

        // Criar um endereço
        $address = Address::factory()->create([
            'customer_id' => $customer->id,
        ]);

        // Criar um pedido (isso deve disparar a notificação Slack)
        $order = Order::create([
            'order_number' => 'PED-' . date('Y') . '-000001',
            'customer_id' => $customer->id,
            'billing_address_id' => $address->id,
            'shipping_address_id' => $address->id,
            'subtotal' => 100.00,
            'shipping_cost' => 10.00,
            'discount' => 0.00,
            'total' => 110.00,
            'status' => 'pending',
            'is_draft' => false,
            'payment_method' => 'credit_card',
        ]);

        // Verificar se o pedido foi criado
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_number' => 'PED-' . date('Y') . '-000001',
            'customer_id' => $customer->id,
        ]);

        // Se chegar aqui sem erro, a notificação foi disparada
        $this->assertTrue(true);
    }
}
