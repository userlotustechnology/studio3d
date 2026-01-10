<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar alguns clientes de exemplo
        $customers = [
            [
                'name' => 'João Silva',
                'email' => 'joao@example.com',
                'phone' => '(11) 98765-4321',
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'phone' => '(21) 99876-5432',
            ],
            [
                'name' => 'Carlos Oliveira',
                'email' => 'carlos@example.com',
                'phone' => '(85) 98765-0123',
            ],
        ];

        foreach ($customers as $customerData) {
            $customer = Customer::firstOrCreate(
                ['email' => $customerData['email']],
                $customerData
            );

            // Criar endereços para cada cliente
            $billingAddress = Address::create([
                'customer_id' => $customer->id,
                'type' => 'residential',
                'street' => 'Rua das Flores',
                'number' => '123',
                'complement' => 'Apto 42',
                'city' => 'São Paulo',
                'state' => 'SP',
                'postal_code' => '01310-100',
                'is_default' => true,
            ]);

            $shippingAddress = Address::create([
                'customer_id' => $customer->id,
                'type' => 'shipping',
                'street' => 'Avenida Paulista',
                'number' => '1000',
                'complement' => 'Sala 200',
                'city' => 'São Paulo',
                'state' => 'SP',
                'postal_code' => '01311-200',
                'is_default' => true,
            ]);
        }
    }
}
