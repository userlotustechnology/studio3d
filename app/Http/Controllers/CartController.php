<?php

namespace App\Http\Controllers;

use App\Events\OrderConfirmed;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Address;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

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

    public function add(string $uuid, Request $request): RedirectResponse
    {
        // Buscar produto pelo UUID
        $product = Product::where('uuid', $uuid)->firstOrFail();

        // Verificar se há usuário logado
        if (auth()->check()) {
            return redirect()->back()->with('error', 'Usuários autenticados não podem adicionar itens ao carrinho neste momento.');
        }

        // Validar estoque (apenas para produtos físicos)
        $quantity = $request->input('quantity', 1);
        if ($product->type === 'physical' && $product->stock < $quantity) {
            return redirect()->back()->with('error', 'Quantidade solicitada indisponível em estoque. Estoque disponível: ' . $product->stock . ' unidades.');
        }
        
        // Obter ou criar order de rascunho
        $order = session()->get('draft_order_id');
        
        if (!$order) {
            // Primeiro produto sendo adicionado - precisa do CPF
            $cpf = $request->input('cpf');
            
            if (!$cpf) {
                // Redirecionar para solicitar CPF
                session()->put('product_to_add', [
                    'product_uuid' => $product->uuid,
                    'quantity' => $quantity
                ]);
                return redirect()->route('cart.request-cpf');
            }

            // Validar CPF (formato básico)
            if (!isValidCPF($cpf)) {
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
                'status' => 'draft',
                'is_draft' => true,
            ]);

            session()->put('draft_order_id', $order->id);
        } else {
            $order = Order::find($order);
            if (!$order) {
                session()->forget('draft_order_id');
                return redirect()->route('cart.index')->with('error', 'Carrinho inválido. Por favor, comece novamente.');
            }
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

    private function calculateShippingCost($cep, $subtotal = null): float
    {
        // Remove máscara do CEP
        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        if (strlen($cep) !== 8) {
            return 0;
        }

        // Verificar se qualifica para frete grátis
        $freeShippingMinimum = \App\Models\Setting::get('free_shipping_minimum', 0);
        
        if ($freeShippingMinimum > 0 && $subtotal !== null && $subtotal >= $freeShippingMinimum) {
            return 0; // Frete grátis!
        }

        // Pegar os 2 primeiros dígitos do CEP para mapear estado
        $cepPrefix = substr($cep, 0, 2);
        
        // Mapeamento de prefixo CEP para estado (primeiros 2 dígitos)
        $cepStateMap = [
            // SP: 01-19
            '01' => 'SP', '02' => 'SP', '03' => 'SP', '04' => 'SP', '05' => 'SP',
            '06' => 'SP', '07' => 'SP', '08' => 'SP', '09' => 'SP', '10' => 'SP',
            '11' => 'SP', '12' => 'SP', '13' => 'SP', '14' => 'SP', '15' => 'SP',
            '16' => 'SP', '17' => 'SP', '18' => 'SP', '19' => 'SP',
            // RJ: 20-28
            '20' => 'RJ', '21' => 'RJ', '22' => 'RJ', '23' => 'RJ', '24' => 'RJ',
            '25' => 'RJ', '26' => 'RJ', '27' => 'RJ', '28' => 'RJ',
            // MG: 30-39
            '30' => 'MG', '31' => 'MG', '32' => 'MG', '33' => 'MG', '34' => 'MG',
            '35' => 'MG', '36' => 'MG', '37' => 'MG', '38' => 'MG', '39' => 'MG',
            // BA: 40-48
            '40' => 'BA', '41' => 'BA', '42' => 'BA', '43' => 'BA', '44' => 'BA',
            '45' => 'BA', '46' => 'BA', '47' => 'BA', '48' => 'BA',
            // DF/GO: 70-72
            '70' => 'DF', '71' => 'DF', '72' => 'GO',
            // ES: 29
            '29' => 'ES',
            // PR: 80-87
            '80' => 'PR', '81' => 'PR', '82' => 'PR', '83' => 'PR', '84' => 'PR',
            '85' => 'PR', '86' => 'PR', '87' => 'PR',
            // SC: 88-89
            '88' => 'SC', '89' => 'SC',
            // RS: 90-99
            '90' => 'RS', '91' => 'RS', '92' => 'RS', '93' => 'RS', '94' => 'RS',
            '95' => 'RS', '96' => 'RS', '97' => 'RS', '98' => 'RS', '99' => 'RS',
        ];

        $stateCode = $cepStateMap[$cepPrefix] ?? null;
        
        if ($stateCode) {
            return ShippingRate::getRate($stateCode);
        }

        return ShippingRate::getRate('XX'); // Padrão para estado desconhecido
    }

    public function calculateShipping(Request $request): \Illuminate\Http\JsonResponse
    {
        $cep = $request->input('cep');
        $draftOrderId = session()->get('draft_order_id');

        if (!$cep || !$draftOrderId) {
            return response()->json(['error' => 'CEP inválido'], 400);
        }

        $order = Order::find($draftOrderId);
        if (!$order) {
            return response()->json(['error' => 'Pedido não encontrado'], 404);
        }

        // Verificar se é endereço de entrega (shipping) ou cobrança (billing)
        $isShippingAddress = $request->input('isShippingAddress', false);

        // Calcular subtotal para verificação de frete grátis
        $items = $order->items()->get();
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item->product_price * $item->quantity;
        }

        // Calcular novo frete (com verificação de frete grátis)
        $shippingCost = $this->calculateShippingCost($cep, $subtotal);

        // Se for endereço de entrega, atualizar o pedido
        if ($isShippingAddress) {
            $total = $subtotal + $shippingCost;

            $order->update([
                'shipping_cost' => $shippingCost,
                'total' => $total,
            ]);
        }

        return response()->json([
            'shippingCost' => $shippingCost,
            'formattedCost' => 'R$ ' . number_format($shippingCost, 2, ',', '.')
        ]);
    }

    private function updateOrderTotals(Order $order): void
    {
        $items = $order->items()->get();
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item->product_price * $item->quantity;
        }

        // Verificar se há CEP na sessão para calcular frete adequado
        $cep = session('cep');
        $shippingCost = 0;
        
        if ($subtotal > 0) {
            if ($cep) {
                $shippingCost = $this->calculateShippingCost($cep, $subtotal);
            } else {
                // Se não há CEP, verificar se qualifica para frete grátis
                $freeShippingMinimum = \App\Models\Setting::get('free_shipping_minimum', 0);
                if ($freeShippingMinimum > 0 && $subtotal >= $freeShippingMinimum) {
                    $shippingCost = 0;
                } else {
                    $shippingCost = 15.00; // Valor padrão temporário
                }
            }
        }

        $total = $subtotal + $shippingCost;

        $order->update([
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
        ]);
    }


    public function update(string $uuid, Request $request): RedirectResponse
    {
        // Buscar produto pelo UUID
        $product = Product::where('uuid', $uuid)->firstOrFail();

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

    public function remove(string $uuid): RedirectResponse
    {
        // Buscar produto pelo UUID
        $product = Product::where('uuid', $uuid)->firstOrFail();

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

        $order = Order::with('items.product', 'customer')->find($draftOrderId);
        
        if (!$order || count($order->items) === 0) {
            throw new \Exception('Seu carrinho está vazio!');
        }

        // Validar estoque de produtos físicos
        $outOfStockItems = [];
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product && $product->type === 'physical' && $product->stock < $item->quantity) {
                $outOfStockItems[] = [
                    'name' => $item->product_name,
                    'requested' => $item->quantity,
                    'available' => $product->stock
                ];
            }
        }

        if (!empty($outOfStockItems)) {
            $message = "Os seguintes produtos não possuem estoque suficiente:\n\n";
            foreach ($outOfStockItems as $item) {
                $message .= "• {$item['name']}: solicitado {$item['requested']}, disponível {$item['available']}\n";
            }
            return redirect()->route('cart.index')->with('error', $message);
        }

        $items = $order->items;
        $subtotal = $order->subtotal;
        $shippingCost = $order->shipping_cost;
        $discount = $order->discount;
        $total = $order->total;
        $customer = $order->customer;

        // Verificar se há produtos digitais no pedido
        $hasDigitalProducts = $order->items()->whereHas('product', function($q) {
            $q->where('type', 'digital');
        })->exists();

        // Verificar se há produtos físicos no pedido
        $hasPhysicalProducts = $order->items()->whereHas('product', function($q) {
            $q->where('type', 'physical');
        })->exists();

        return view('shop.checkout', compact('order', 'items', 'subtotal', 'shippingCost', 'discount', 'total', 'customer', 'hasDigitalProducts', 'hasPhysicalProducts'));
    }

    public function processCheckout(Request $request): RedirectResponse
    {
        $draftOrderId = session()->get('draft_order_id');

        if (!$draftOrderId) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio!');
        }

        $order = Order::with('items.product', 'customer')->find($draftOrderId);
        
        if (!$order || count($order->items) === 0) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio!');
        }

        // Validar valor mínimo do pedido (não incluindo frete)
        $minimumOrderValue = \App\Models\Setting::get('minimum_order_value', 0);
        if ($minimumOrderValue > 0 && $order->subtotal < $minimumOrderValue) {
            return redirect()->route('cart.index')->with('error', 
                "Valor mínimo do pedido é R$ " . number_format($minimumOrderValue, 2, ',', '.') . 
                ". Seu carrinho tem R$ " . number_format($order->subtotal, 2, ',', '.')
            );
        }

        // Validar estoque de produtos físicos
        $outOfStockItems = [];
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product && $product->type === 'physical' && $product->stock < $item->quantity) {
                $outOfStockItems[] = [
                    'name' => $item->product_name,
                    'requested' => $item->quantity,
                    'available' => $product->stock
                ];
            }
        }

        if (!empty($outOfStockItems)) {
            $message = "Os seguintes produtos não possuem estoque suficiente:\n\n";
            foreach ($outOfStockItems as $item) {
                $message .= "• {$item['name']}: solicitado {$item['requested']}, disponível {$item['available']}\n";
            }
            return redirect()->route('cart.index')->with('error', $message);
        }

        // Verificar se há produtos físicos no pedido
        $hasPhysicalProducts = $order->items()->whereHas('product', function($q) {
            $q->where('type', 'physical');
        })->exists();

        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'billing_street' => 'required|string',
            'billing_number' => 'required|string',
            'billing_complement' => 'nullable|string',
            'billing_neighborhood' => 'required|string',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string|size:2',
            'billing_postal_code' => 'required|string',
            'payment_method' => 'required|in:credit_card,debit_card,pix,boleto,paypal',
        ];

        // Validar endereço de entrega apenas se houver produtos físicos
        if ($hasPhysicalProducts) {
            // Se o endereço de entrega é diferente, validar seus campos
            if ($request->input('different_address') === 'on' || $request->input('different_address') === 'true' || $request->input('different_address') === '1') {
                $rules = array_merge($rules, [
                    'shipping_street' => 'required|string',
                    'shipping_number' => 'required|string',
                    'shipping_complement' => 'nullable|string',
                    'shipping_neighborhood' => 'required|string',
                    'shipping_city' => 'required|string',
                    'shipping_state' => 'required|string|size:2',
                    'shipping_postal_code' => 'required|string',
                ]);
            }
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
            'street' => $validated['billing_street'],
            'number' => $validated['billing_number'],
            'complement' => $validated['billing_complement'] ?? null,
            'city' => $validated['billing_city'],
            'state' => $validated['billing_state'],
            'postal_code' => $validated['billing_postal_code'],
            'notes' => $validated['billing_neighborhood'],
        ]);

        // Criar endereço de entrega apenas se houver produtos físicos
        $shippingAddress = null;
        if ($hasPhysicalProducts) {
            if ($request->input('different_address') === 'on' || $request->input('different_address') === 'true' || $request->input('different_address') === '1') {
                // Usar dados do endereço de entrega diferente
                $shippingAddress = Address::create([
                    'customer_id' => $customer->id,
                    'type' => 'shipping',
                    'street' => $validated['shipping_street'],
                    'number' => $validated['shipping_number'],
                    'complement' => $validated['shipping_complement'] ?? null,
                    'city' => $validated['shipping_city'],
                    'state' => $validated['shipping_state'],
                    'postal_code' => $validated['shipping_postal_code'],
                    'notes' => $validated['shipping_neighborhood'],
                ]);
            } else {
                // Usar o mesmo endereço de cobrança como entrega
                $shippingAddress = Address::create([
                    'customer_id' => $customer->id,
                    'type' => 'shipping',
                    'street' => $validated['billing_street'],
                    'number' => $validated['billing_number'],
                    'complement' => $validated['billing_complement'] ?? null,
                    'city' => $validated['billing_city'],
                    'state' => $validated['billing_state'],
                    'postal_code' => $validated['billing_postal_code'],
                    'notes' => $validated['billing_neighborhood'],
                ]);
            }
        }

        // Finalizar o pedido (remover draft status)
        $order->update([
            'billing_address_id' => $billingAddress->id,
            'shipping_address_id' => $shippingAddress?->id,
            'payment_method' => $validated['payment_method'],
            'status' => 'pending', // Transição de 'draft' para 'pending'
            'is_draft' => false,
            'order_number' => 'PED-' . date('Y') . '-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'access_token' => $order->generateAccessToken(),
        ]);

        // Registrar transição de status no histórico
        \App\Models\OrderStatusHistory::create([
            'order_id' => $order->id,
            'from_status' => 'draft',
            'to_status' => 'pending',
            'reason' => 'Pedido finalizado pelo cliente',
            'changed_by' => 'customer',
        ]);

        // Disparar evento de pedido confirmado para notificação no Slack
        \Illuminate\Support\Facades\Log::info('Dispatching OrderConfirmed event for order: ' . $order->id);
        OrderConfirmed::dispatch($order);

        // Enviar email de confirmação de pedido (assíncrono com delay de 1 minuto)
        Mail::queue(new OrderConfirmationMail($order));

        // Limpar sessão do carrinho
        session()->forget('draft_order_id');
        session()->forget('product_to_add');

        return redirect()->route('order.success', $order->uuid)->with('success', 'Pedido realizado com sucesso!');
    }

    public function orderSuccess(string $uuid): View
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
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

        return view('shop.order-tracking', compact('order'));
    }

    /**
     * Retorna a contagem de itens no carrinho (para o contador do header)
     */
    public function count()
    {
        $draftOrderId = session()->get('draft_order_id');
        $count = 0;

        if ($draftOrderId) {
            $order = Order::find($draftOrderId);
            if ($order) {
                $count = $order->items()->sum('quantity');
            }
        }

        return response()->json(['count' => $count]);
    }

    /**
     * Acompanha um pedido pelo número do pedido (para links nos emails)
     * Requer um token de acesso válido para evitar acesso indevido
     */
    public function trackOrder(string $orderNumber, string $token): View
    {
        // Buscar pedido pelo order_number
        $order = Order::with('items', 'customer', 'billingAddress', 'shippingAddress')
            ->where('order_number', $orderNumber)
            ->where('is_draft', false)
            ->firstOrFail();

        // Validar token de acesso
        if (!$order->access_token || $order->access_token !== $token) {
            abort(403, 'Acesso negado. Token inválido ou expirado.');
        }

        return view('shop.order-tracking', compact('order'));
    }

}