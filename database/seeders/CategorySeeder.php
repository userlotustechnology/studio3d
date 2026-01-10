<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Eletrônicos',
                'description' => 'Produtos eletrônicos diversos',
                'is_active' => true,
            ],
            [
                'name' => 'Roupas',
                'description' => 'Vestuário e acessórios',
                'is_active' => true,
            ],
            [
                'name' => 'Alimentos',
                'description' => 'Produtos alimentícios',
                'is_active' => true,
            ],
            [
                'name' => 'Móveis',
                'description' => 'Móveis para residência',
                'is_active' => true,
            ],
            [
                'name' => 'Livros',
                'description' => 'Livros diversos',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
