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

            // Remove máscara do CPF antes de usar
            $cpf = preg_replace('/[^0-9]/', '', $cpf);

            // Buscar ou criar cliente pelo CPF (sem máscara)
            $customer = Customer::where('cpf', $cpf)->first();
            
            if (!$customer) {
                // Criar novo cliente
                $customer = Customer::create([
                    'name' => 'Cliente ' . $cpf,
                    'email' => 'cliente-' . time() . '@temp.local',
                    'cpf' => $cpf,
                ]);
            }

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

        // Verificar se o produto já está no pedido
        $existingItem = $order->items()->where('product_id', $product->id)->first();
        
        if ($existingItem) {
            // Se o produto já existe, incrementar a quantidade
            $newQuantity = $existingItem->quantity + $quantity;
            $existingItem->update([
                'quantity' => $newQuantity,
                'subtotal' => $product->price * $newQuantity,
            ]);
        } else {
            // Se o produto não existe, criar um novo OrderItem
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity,
            ]);
        }

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
            $subtotal += $item->product_price * $item->quantity;
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
        $draftOrderId = session()->get('draft_order_id');

        if (!$draftOrderId) {
            return redirect()->route('cart.index')->with('error', 'Carrinho não encontrado!');
        }

        $order = Order::find($draftOrderId);
        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Pedido não encontrado!');
        }

        $orderItem = $order->items()->where('product_id', $product->id)->first();
        
        if ($orderItem) {
            if ($quantity <= 0) {
                $orderItem->delete();
            } else {
                $orderItem->update(['quantity' => $quantity]);
            }
            
            $this->updateOrderTotals($order);
        }

        return redirect()->route('cart.index')->with('success', 'Carrinho atualizado!');
    }

    public function remove(Product $product): RedirectResponse
    {
        $draftOrderId = session()->get('draft_order_id');

        if (!$draftOrderId) {
            return redirect()->route('cart.index')->with('error', 'Carrinho não encontrado!');
        }

        $order = Order::find($draftOrderId);
        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Pedido não encontrado!');
        }

        $orderItem = $order->items()->where('product_id', $product->id)->first();
        if ($orderItem) {
            $orderItem->delete();
            $this->updateOrderTotals($order);
        }

        return redirect()->route('cart.index')->with('success', 'Produto removido do carrinho!');
    }

    public function clear(): RedirectResponse
    {
        $draftOrderId = session()->get('draft_order_id');

        if ($draftOrderId) {
            $order = Order::find($draftOrderId);
            if ($order) {
                $order->items()->delete();
                $order->delete();
            }
        }
        
        session()->forget('draft_order_id');
        session()->forget('product_to_add');
        
        return redirect()->route('cart.index')->with('success', 'Carrinho limpo!');
    }

    public function checkout(): View
    {
        $draftOrderId = session()->get('draft_order_id');

        if (!$draftOrderId) {
            // Redirecionar sem retornar - usar exceção ou throw
            throw new \Exception('Seu carrinho está vazio!');
        }

        $order = Order::with('items', 'customer')->find($draftOrderId);
        
        if (!$order || count($order->items) === 0) {
            throw new \Exception('Seu carrinho está vazio!');
        }

        $items = $order->items;
        $subtotal = $order->subtotal;
        $shippingCost = $order->shipping_cost;
        $discount = $order->discount;
        $total = $order->total;
        $customer = $order->customer;

        return view('shop.checkout', compact('order', 'items', 'subtotal', 'shippingCost', 'discount', 'total', 'customer'));
    }

    public function processCheckout(Request $request): RedirectResponse
    {
        $draftOrderId = session()->get('draft_order_id');

        if (!$draftOrderId) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio!');
        }

        $order = Order::with('items', 'customer')->find($draftOrderId);
        
        if (!$order || count($order->items) === 0) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio!');
        }

        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string|size:2',
            'billing_postal_code' => 'required|string',
            'payment_method' => 'required|in:credit_card,debit_card,pix,boleto,paypal',
        ];

        // Se o endereço de entrega é diferente, validar seus campos
        if ($request->input('different_address') === 'on' || $request->input('different_address') === 'true' || $request->input('different_address') === '1') {
            $rules = array_merge($rules, [
                'shipping_address' => 'required|string',
                'shipping_city' => 'required|string',
                'shipping_state' => 'required|string|size:2',
                'shipping_postal_code' => 'required|string',
            ]);
        }

        $validated = $request->validate($rules);

        // Atualizar cliente com dados adicionais
        $customer = $order->customer;
        $customer->update([
            'name' => $validated['customer_name'],
            'email' => $validated['customer_email'],
            'phone' => $validated['customer_phone'],
        ]);
        
        // Criar endereço de cobrança
        $billingAddress = Address::create([
            'customer_id' => $customer->id,
            'type' => 'residential',
            'street' => $validated['billing_address'],
            'number' => '0',
            'city' => $validated['billing_city'],
            'state' => $validated['billing_state'],
            'postal_code' => $validated['billing_postal_code'],
        ]);

        // Criar endereço de entrega
        if ($request->input('different_address') === 'on' || $request->input('different_address') === 'true' || $request->input('different_address') === '1') {
            // Usar dados do endereço de entrega diferente
            $shippingAddress = Address::create([
                'customer_id' => $customer->id,
                'type' => 'shipping',
                'street' => $validated['shipping_address'],
                'number' => '0',
                'city' => $validated['shipping_city'],
                'state' => $validated['shipping_state'],
                'postal_code' => $validated['shipping_postal_code'],
            ]);
        } else {
            // Usar o mesmo endereço de cobrança como entrega
            $shippingAddress = Address::create([
                'customer_id' => $customer->id,
                'type' => 'shipping',
                'street' => $validated['billing_address'],
                'number' => '0',
                'city' => $validated['billing_city'],
                'state' => $validated['billing_state'],
                'postal_code' => $validated['billing_postal_code'],
            ]);
        }

        // Finalizar o pedido (remover draft status)
        $order->update([
            'billing_address_id' => $billingAddress->id,
            'shipping_address_id' => $shippingAddress->id,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'is_draft' => false,
            'order_number' => 'PED-' . date('Y') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
        ]);

        // Limpar sessão do carrinho
        session()->forget('draft_order_id');
        session()->forget('product_to_add');

        return redirect()->route('order.success', $order->id)->with('success', 'Pedido realizado com sucesso!');
    }

    public function orderSuccess(Order $order): View
    {
        $order->load('items', 'customer', 'billingAddress', 'shippingAddress');
        return view('shop.order-success', compact('order'));
    }

    public function searchOrdersForm(): View
    {
        return view('shop.order-search');
    }

    public function searchOrders(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'cpf' => 'required|string|min:11',
            'order_id' => 'required|string',
        ], [
            'cpf.required' => 'O CPF é obrigatório',
            'cpf.min' => 'O CPF deve ter no mínimo 11 dígitos',
            'order_id.required' => 'O ID do pedido é obrigatório',
        ]);

        // Remove formatação do CPF
        $cpf = preg_replace('/[^0-9]/', '', $validated['cpf']);

        // Extrai o ID numérico do formato PED-YYYY-XXXXXX
        $orderId = $validated['order_id'];
        
        // Se veio no formato completo PED-2026-000001, extrai apenas o número
        if (preg_match('/^PED-\d{4}-(\d+)$/', $orderId, $matches)) {
            $orderId = (int) $matches[1];
        } else {
            // Se veio só o número, converte
            $orderId = (int) $orderId;
        }

        if ($orderId <= 0) {
            return back()->with('error', 'O ID do pedido é inválido. Use o formato PED-YYYY-XXXXXX ou apenas o número.');
        }

        // Buscar o cliente pelo CPF
        $customer = Customer::where('cpf', $cpf)->first();

        if (!$customer) {
            return back()->with('error', 'Nenhum cliente encontrado com este CPF.');
        }

        // Buscar o pedido pelo ID e verificar se pertence ao cliente
        $order = Order::with('items', 'customer', 'billingAddress', 'shippingAddress')
            ->where('id', $orderId)
            ->where('customer_id', $customer->id)
            ->where('is_draft', false)
            ->first();

        if (!$order) {
            return back()->with('error', 'Pedido não encontrado. Verifique o CPF e o ID do pedido.');
        }

        return view('shop.order-details', compact('order'));
    }
}
