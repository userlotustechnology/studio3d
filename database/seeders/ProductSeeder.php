<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Eletrônicos
            [
                'name' => 'Fone de Ouvido Wireless',
                'description' => 'Fone de ouvido Bluetooth com cancelamento de ruído ativo, bateria de 30h e qualidade de som premium.',
                'price' => 299.99,
                'category' => 'Eletrônicos',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop',
                'is_active' => true,
            ],
            [
                'name' => 'Smartwatch Pro',
                'description' => 'Relógio inteligente com monitor cardíaco, GPS integrado e 5 dias de bateria.',
                'price' => 449.99,
                'category' => 'Eletrônicos',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop',
                'is_active' => true,
            ],
            [
                'name' => 'Câmera Portátil',
                'description' => 'Câmera compacta 4K com estabilização de imagem, perfeita para viagens e conteúdo.',
                'price' => 599.99,
                'category' => 'Eletrônicos',
                'image' => 'https://images.unsplash.com/photo-1612198188060-c7c2a3b66eae?w=400&h=400&fit=crop',
                'is_active' => true,
            ],

            // Moda
            [
                'name' => 'Camiseta Premium',
                'description' => 'Camiseta 100% algodão com design moderno e confortável para uso diário.',
                'price' => 89.99,
                'category' => 'Moda',
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop',
                'is_active' => true,
            ],
            [
                'name' => 'Jaqueta de Couro',
                'description' => 'Jaqueta de couro genuíno com design clássico e durável.',
                'price' => 799.99,
                'category' => 'Moda',
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16ebc5?w=400&h=400&fit=crop',
                'is_active' => true,
            ],
            [
                'name' => 'Calça Jeans Premium',
                'description' => 'Calça jeans de alta qualidade com conforto e durabilidade.',
                'price' => 199.99,
                'category' => 'Moda',
                'image' => 'https://images.unsplash.com/photo-1542272604-787c62d465d1?w=400&h=400&fit=crop',
                'is_active' => true,
            ],

            // Casa e Decoração
            [
                'name' => 'Luminária LED Inteligente',
                'description' => 'Luminária com controle remoto, 16 milhões de cores e compatível com assistentes de voz.',
                'price' => 129.99,
                'category' => 'Casa e Decoração',
                'image' => 'https://images.unsplash.com/photo-1565636192335-14c58a1b4f08?w=400&h=400&fit=crop',
                'is_active' => true,
            ],
            [
                'name' => 'Almofada Decorativa',
                'description' => 'Almofada de veludo com design exclusivo e enchimento confortável.',
                'price' => 59.99,
                'category' => 'Casa e Decoração',
                'image' => 'https://images.unsplash.com/photo-1599942676399-3c40760be8f8?w=400&h=400&fit=crop',
                'is_active' => true,
            ],
            [
                'name' => 'Vasos Decorativos (Jogo 3)',
                'description' => 'Conjunto de 3 vasos em cerâmica com diferentes tamanhos e cores neutras.',
                'price' => 149.99,
                'category' => 'Casa e Decoração',
                'image' => 'https://images.unsplash.com/photo-1578500494198-246f612d03b3?w=400&h=400&fit=crop',
                'is_active' => true,
            ],

            // Esportes
            [
                'name' => 'Tênis Esportivo',
                'description' => 'Tênis com tecnologia de amortecimento avançado para corrida e treino.',
                'price' => 379.99,
                'category' => 'Esportes',
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop',
                'is_active' => true,
            ],
            [
                'name' => 'Kit de Pesos Ajustáveis',
                'description' => 'Halteres ajustáveis de 2kg a 10kg para musculação em casa.',
                'price' => 299.99,
                'category' => 'Esportes',
                'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=400&fit=crop',
                'is_active' => true,
            ],
            [
                'name' => 'Bicicleta Mountain Bike',
                'description' => 'Bicicleta com 21 marchas, suspensão frontal e pneus off-road.',
                'price' => 899.99,
                'category' => 'Esportes',
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=400&fit=crop',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
