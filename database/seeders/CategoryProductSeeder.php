<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar categoria Brinquedo
        $toyCategory = Category::create([
            'name' => 'Brinquedo',
            'description' => 'Categoria de brinquedos diversos',
            'is_active' => true,
        ]);

        // Criar produto teste
        Product::create([
            'name' => 'Brinquedo Teste',
            'description' => 'Produto teste para validação de sistema',
            'price' => 20.14,
            'cost_price' => 14.56,
            'category_id' => $toyCategory->id,
            'sku' => 'BRINQ-TESTE-001',
            'type' => 'physical',
            'stock' => 100,
            'is_active' => true,
        ]);
    }
}
