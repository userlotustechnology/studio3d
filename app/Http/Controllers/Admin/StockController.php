<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StockController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category'])
            ->orderBy('name')
            ->paginate(20);
            
        return view('admin.stock.index', compact('products'));
    }

    public function movements(): View
    {
        $movements = StockMovement::with(['product', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            
        return view('admin.stock.movements', compact('movements'));
    }

    public function productMovements(Product $product): View
    {
        $movements = StockMovement::with(['order'])
            ->where('product_id', $product->id)
            ->orderBy('created_at', 'desc')
            ->paginate(30);
            
        return view('admin.stock.product-movements', compact('product', 'movements'));
    }

    public function adjust(Product $product): View
    {
        return view('admin.stock.adjust', compact('product'));
    }

    public function processAdjustment(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'new_stock' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
        ]);

        $currentStock = $product->stock;
        $newStock = $validated['new_stock'];
        $difference = $newStock - $currentStock;

        if ($difference != 0) {
            $type = $difference > 0 ? 'in' : 'out';
            
            StockMovement::recordMovement(
                $product->id,
                $type,
                abs($difference),
                null,
                $validated['reason'],
                auth()->id()
            );

            return redirect()->route('admin.stock.product-movements', $product)
                ->with('success', 'Estoque ajustado com sucesso!');
        }

        return redirect()->route('admin.stock.product-movements', $product)
            ->with('info', 'Nenhuma alteração no estoque foi necessária.');
    }
}
