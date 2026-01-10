<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function shop(): View
    {
        $products = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Product::where('is_active', true)
            ->distinct()
            ->pluck('category');

        return view('shop.index', compact('products', 'categories'));
    }

    public function category(string $category): View
    {
        $products = Product::where('is_active', true)
            ->where('category', $category)
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Product::where('is_active', true)
            ->distinct()
            ->pluck('category');

        return view('shop.category', compact('products', 'categories', 'category'));
    }
}
