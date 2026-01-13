<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

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

    public function show(string $uuid): View
    {
        $product = Product::where('is_active', true)->where('uuid', $uuid)->with(['category','images'])->firstOrFail();
        
        $relatedProducts = Product::where('is_active', true)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
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
            'cost_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|unique:products,sku',
            'type' => 'required|in:physical,digital',
            'product_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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

        $validated['is_active'] = $request->has('is_active');

        $product = Product::create($validated);

        // store multiple images
        if ($request->hasFile('images')) {
            $disk = app()->environment('production') ? 's3' : 'public';
            $isFirst = true;
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', $disk);
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_main' => $isFirst, // First image is main by default
                ]);
                $isFirst = false;
            }
        }

        // Set specific main image if selected
        if ($request->filled('main_image_index')) {
            $mainIndex = (int) $request->input('main_image_index');
            $images = $product->images()->orderBy('id')->get();
            if ($images->count() > $mainIndex) {
                $images[$mainIndex]->setAsMain();
            }
        }

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
            'cost_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'type' => 'required|in:physical,digital',
            'product_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|exists:product_images,id',
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

        // remove selected images
        if ($request->filled('remove_images')) {
            $toRemove = $request->input('remove_images', []);
            foreach ($toRemove as $imgId) {
                $img = \App\Models\ProductImage::find($imgId);
                if ($img && $img->product_id === $product->id) {
                    Storage::disk('public')->delete($img->path);
                    Storage::disk('s3')->delete($img->path);
                    $img->delete();
                }
            }
        }

        // add new uploaded images
        if ($request->hasFile('images')) {
            $disk = app()->environment('production') ? 's3' : 'public';
            $hasMain = $product->images()->where('is_main', true)->exists();
            $isFirst = !$hasMain; // If no main image, first new one becomes main
            
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', $disk);
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_main' => $isFirst,
                ]);
                $isFirst = false;
            }
        }

        // Set specific main image if selected
        if ($request->filled('main_image_id')) {
            $mainImageId = (int) $request->input('main_image_id');
            $img = \App\Models\ProductImage::find($mainImageId);
            if ($img && $img->product_id === $product->id) {
                $img->setAsMain();
            }
        }

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // delete all related images from both disks (if any)
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->path);
            Storage::disk('s3')->delete($img->path);
            $img->delete();
        }

        // delete legacy image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
            Storage::disk('s3')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produto deletado com sucesso!');
    }
}
