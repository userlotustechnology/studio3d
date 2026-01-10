<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    // SHOP METHODS
    public function shop(): View
    {
        $products = Product::where('is_active', true)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('shop.index', compact('products', 'categories'));
    }

    public function category(Category $category): View
    {
        $products = Product::where('is_active', true)
            ->where('category_id', $category->id)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('shop.category', compact('products', 'categories', 'category'));
    }

    public function show(int $id): View
    {
        $product = Product::where('is_active', true)->with('category')->findOrFail($id);
        
        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->with('category')
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }

    // ADMIN METHODS
    public function index(): View
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(15);
        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->pluck('name', 'id');
        return view('products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|unique:products,sku',
            'type' => 'required|in:physical,digital',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ];

        // Estoque é obrigatório apenas para produtos físicos
        if ($request->input('type') === 'physical') {
            $rules['stock'] = 'required|integer|min:0';
        } else {
            $rules['stock'] = 'nullable|integer|min:0';
        }

        $validated = $request->validate($rules);

        // Se for digital e não informou estoque, define como 0
        if ($request->input('type') === 'digital' && !$request->input('stock')) {
            $validated['stock'] = 0;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto criado com sucesso!');
    }

    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->pluck('name', 'id');
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'type' => 'required|in:physical,digital',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ];

        // Estoque é obrigatório apenas para produtos físicos
        if ($request->input('type') === 'physical') {
            $rules['stock'] = 'required|integer|min:0';
        } else {
            $rules['stock'] = 'nullable|integer|min:0';
        }

        $validated = $request->validate($rules);

        // Se for digital e não informou estoque, define como 0
        if ($request->input('type') === 'digital' && !$request->input('stock')) {
            $validated['stock'] = 0;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto deletado com sucesso!');
    }
}
