<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $draftOrderId = session()->get('draft_order_id');
        $items = [];
        $subtotal = 0;
        $shippingCost = 0;
        $total = 0;

        if ($draftOrderId) {
            $order = Order::find($draftOrderId);
            if ($order) {
                $items = $order->items;
                $subtotal = $order->subtotal;
                $shippingCost = $order->shipping_cost;
                $total = $order->total;
            }
        }

        return view('shop.cart', compact('items', 'subtotal', 'shippingCost', 'total', 'draftOrderId'));
    }

    public function add(Product $product, Request $request): RedirectResponse
    {
        // Verificar se há usuário logado
        if (auth()->check()) {
            return redirect()->back()->with('error', 'Usuários autenticados não podem adicionar itens ao carrinho neste momento.');
        }

        $quantity = $request->input('quantity', 1);
        
        // Obter ou criar order de rascunho
        $order = session()->get('draft_order_id');
        
        if (!$order) {
            // Primeiro produto sendo adicionado - precisa do CPF
            $cpf = $request->input('cpf');
            
            if (!$cpf) {
                // Redirecionar para solicitar CPF
                session()->put('product_to_add', [
                    'product_id' => $product->id,
                    'quantity' => $quantity
                ]);
                return redirect()->route('cart.request-cpf');
            }

            // Validar CPF (formato básico)
            if (!$this->isValidCPF($cpf)) {
                return redirect()->back()->with('error', 'CPF inválido!');
            }

            // Criar cliente
            $customer = Customer::create([
                'name' => 'Cliente ' . $cpf,
                'email' => 'cliente-' . time() . '@temp.local',
                'cpf' => $cpf,
            ]);

            // Criar order de rascunho
            $order = Order::create([
                'order_number' => 'PED-DRAFT-' . $customer->id . '-' . time(),
                'customer_id' => $customer->id,
                'subtotal' => 0,
                'shipping_cost' => 0,
                'discount' => 0,
                'total' => 0,
                'status' => 'pending',
                'is_draft' => true,
            ]);

            session()->put('draft_order_id', $order->id);
        } else {
            $order = Order::find($order);
        }

        // Adicionar produto ao pedido
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
        ]);

        // Atualizar totais do pedido
        $this->updateOrderTotals($order);

        return redirect()->route('cart.index')->with('success', 'Produto adicionado ao carrinho!');
    }

    public function requestCpf(): View
    {
        $productToAdd = session()->get('product_to_add');
        return view('shop.request-cpf', compact('productToAdd'));
    }

    private function isValidCPF(string $cpf): bool
    {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se tem 11 dígitos
        if (strlen($cpf) !== 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        // Validação do primeiro dígito verificador
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }
        $digit1 = 11 - ($sum % 11);
        $digit1 = $digit1 > 9 ? 0 : $digit1;

        if ($cpf[9] != $digit1) {
            return false;
        }

        // Validação do segundo dígito verificador
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }
        $digit2 = 11 - ($sum % 11);
        $digit2 = $digit2 > 9 ? 0 : $digit2;

        if ($cpf[10] != $digit2) {
            return false;
        }

        return true;
    }

    private function updateOrderTotals(Order $order): void
    {
        $items = $order->items()->get();
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item->price * $item->quantity;
        }

        $shippingCost = $subtotal > 0 ? 15.00 : 0;
        $total = $subtotal + $shippingCost;

        $order->update([
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
        ]);
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

        // Criar ou obter cliente
        $customer = Customer::firstOrCreate(
            ['email' => $validated['customer_email']],
            [
                'name' => $validated['customer_name'],
                'phone' => $validated['customer_phone'],
            ]
        );

        // Criar endereço de cobrança
        $billingAddress = Address::create([
            'customer_id' => $customer->id,
            'type' => 'residential',
            'street' => $validated['shipping_address'],
            'number' => '0',
            'city' => $validated['city'],
            'state' => $validated['state'],
            'postal_code' => $validated['postal_code'],
        ]);

        // Usar o mesmo endereço para entrega
        $shippingAddress = $billingAddress;

        // Criar pedido
        $order = Order::create([
            'order_number' => 'PED-' . date('YmdHis') . '-' . random_int(100, 999),
            'customer_id' => $customer->id,
            'billing_address_id' => $billingAddress->id,
            'shipping_address_id' => $shippingAddress->id,
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
