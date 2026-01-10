<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $itemTotal = $product->price * $quantity;
                $subtotal += $itemTotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $itemTotal,
                ];
            }
        }

        $shippingCost = $subtotal > 0 ? 15.00 : 0;
        $total = $subtotal + $shippingCost;

        return view('shop.cart', compact('items', 'subtotal', 'shippingCost', 'total'));
    }

    public function add(Product $product, Request $request): RedirectResponse
    {
        $quantity = $request->input('quantity', 1);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id] += $quantity;
        } else {
            $cart[$product->id] = $quantity;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produto adicionado ao carrinho!');
    }

    public function update(Product $product, Request $request): RedirectResponse
    {
        $quantity = $request->input('quantity', 1);
        $cart = session()->get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = $quantity;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Carrinho atualizado!');
    }

    public function remove(Product $product): RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produto removido do carrinho!');
    }

    public function clear(): RedirectResponse
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Carrinho limpo!');
    }

    public function checkout(): View
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio!');
        }

        $items = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $itemTotal = $product->price * $quantity;
                $subtotal += $itemTotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $itemTotal,
                ];
            }
        }

        $shippingCost = 15.00;
        $discount = 0;
        $total = $subtotal + $shippingCost - $discount;

        return view('shop.checkout', compact('items', 'subtotal', 'shippingCost', 'discount', 'total'));
    }

    public function processCheckout(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio!');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string|size:2',
            'postal_code' => 'required|string',
            'payment_method' => 'required|in:credit_card,debit_card,pix,boleto,paypal',
        ]);

        // Calcular totais
        $subtotal = 0;
        $items = [];

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $itemTotal = $product->price * $quantity;
                $subtotal += $itemTotal;
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $itemTotal,
                ];
            }
        }

        $shippingCost = 15.00;
        $discount = 0;
        $total = $subtotal + $shippingCost - $discount;

        // Criar pedido
        $order = Order::create([
            'order_number' => 'PED-' . date('YmdHis') . '-' . random_int(100, 999),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'shipping_address' => $validated['shipping_address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'],
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount' => $discount,
            'total' => $total,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);

        // Criar itens do pedido
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'product_price' => $item['product_price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        // Limpar carrinho
        session()->forget('cart');

        return redirect()->route('order.success', $order->id)->with('success', 'Pedido realizado com sucesso!');
    }

    public function orderSuccess(Order $order): View
    {
        return view('shop.order-success', compact('order'));
    }
}
