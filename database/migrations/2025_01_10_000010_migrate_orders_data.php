<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Address;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrar dados existentes da tabela orders para customers e addresses
        $orders = DB::table('orders')->get();

        foreach ($orders as $order) {
            // Criar ou obter o customer
            $customer = Customer::firstOrCreate(
                ['email' => $order->customer_email],
                [
                    'name' => $order->customer_name,
                    'phone' => $order->customer_phone,
                ]
            );

            // Criar endereço de cobrança
            $billingAddress = Address::create([
                'customer_id' => $customer->id,
                'type' => 'residential',
                'street' => $order->shipping_address,
                'number' => '0',
                'city' => $order->city,
                'state' => $order->state,
                'postal_code' => $order->postal_code,
                'is_default' => true,
            ]);

            // Criar endereço de entrega (igual ao de cobrança por padrão)
            $shippingAddress = Address::create([
                'customer_id' => $customer->id,
                'type' => 'shipping',
                'street' => $order->shipping_address,
                'number' => '0',
                'city' => $order->city,
                'state' => $order->state,
                'postal_code' => $order->postal_code,
                'is_default' => true,
            ]);

            // Atualizar ordem com as novas referências
            DB::table('orders')
                ->where('id', $order->id)
                ->update([
                    'customer_id' => $customer->id,
                    'billing_address_id' => $billingAddress->id,
                    'shipping_address_id' => $shippingAddress->id,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Limpar customers e addresses criados durante a migração
        DB::table('orders')->whereNotNull('customer_id')->update([
            'customer_id' => null,
            'billing_address_id' => null,
            'shipping_address_id' => null,
        ]);

        DB::table('addresses')->truncate();
        DB::table('customers')->truncate();
    }
};
