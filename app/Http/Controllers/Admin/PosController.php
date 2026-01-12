<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\ShippingRate;
use App\Models\Setting;
use App\Events\OrderConfirmed;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class PosController extends Controller
{
    public function index(): View
    {
        $recentOrders = Order::where('is_draft', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->with('customer')
            ->get();

        return view('admin.pos.index', compact('recentOrders'));
    }

    public function create(): View
    {
        $products = Product::where('is_active', true)->get();
        $customers = Customer::orderBy('name')->get();
        
        return view('admin.pos.create', compact('products', 'customers'));
    }

    public function searchProducts(Request $request): JsonResponse
    {
        try {
            $query = $request->get('query');
            
            if (empty($query) || strlen($query) < 2) {
                return response()->json([]);
            }
            
            $products = Product::where('is_active', true)
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('sku', 'LIKE', "%{$query}%");
                })
                ->limit(10)
                ->get(['id', 'name', 'sku', 'price', 'stock']);

            return response()->json($products);
        } catch (\Exception $e) {
            \Log::error('Erro na busca de produtos: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro interno do servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchCustomers(Request $request): JsonResponse
    {
        $query = $request->get('query');
        
        $customers = Customer::with('addresses')
            ->where(function($q) use ($query) {
                $q->where('phone', 'LIKE', "%{$query}%")
                  ->orWhere('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('cpf', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json($customers);
    }

    public function searchByCpf(Request $request): JsonResponse
    {
        $cpf = preg_replace('/[^0-9]/', '', $request->get('cpf'));
        
        $customer = Customer::with('addresses')
            ->where('cpf', $cpf)
            ->first();
        
        return response()->json([
            'found' => $customer ? true : false,
            'customer' => $customer
        ]);
    }

    public function createCustomer(Request $request): JsonResponse
    {
        $cpf = preg_replace('/[^0-9]/', '', $request->input('cpf', ''));
        
        // Validar CPF se fornecido
        if (!empty($cpf) && !isValidCPF($cpf)) {
            return response()->json([
                'success' => false,
                'message' => 'CPF inválido. Por favor, verifique os números digitados.'
            ], 422);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone' => 'nullable|string|max:20',
            'cpf' => 'required|string|size:11|unique:customers'
        ]);

        $customer = Customer::create($validated);
        $customer->load('addresses');

        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
    }

    public function createAddress(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'street' => 'required|string|max:255',
                'number' => 'required|string|max:20',
                'complement' => 'nullable|string|max:100',
                'neighborhood' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'state' => 'required|string|size:2',
                'cep' => 'required|string|max:15'
            ]);

            // Padronizar CEP
            $postalCode = preg_replace('/[^0-9]/', '', $validated['cep']);

            // Criar endereço de entrega e cobrança (ambos como shipping)
            $address = Address::create([
                'customer_id' => $validated['customer_id'],
                'type' => 'shipping',
                'street' => $validated['street'],
                'number' => $validated['number'],
                'complement' => $validated['complement'],
                'neighborhood' => $validated['neighborhood'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $postalCode
            ]);

            // Recarregar cliente com endereços
            $customer = Customer::with('addresses')->find($validated['customer_id']);

            return response()->json([
                'success' => true,
                'message' => 'Endereço cadastrado com sucesso!',
                'address' => $address,
                'customer' => $customer
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao criar endereço: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar endereço: ' . $e->getMessage()
            ], 500);
        }
    }

    public function calculateShipping(Request $request): JsonResponse
    {
        $subtotal = $request->input('total', 0);
        $addressId = $request->input('address_id');
        $manualAddress = $request->input('manual_address');
        
        $shippingCost = 15.00; // Frete padrão
        $cep = null;
        
        if ($addressId) {
            // Usar endereço cadastrado
            $address = Address::find($addressId);
            if ($address && $address->cep) {
                $cep = preg_replace('/[^0-9]/', '', $address->cep);
            }
        } elseif ($manualAddress) {
            // Usar endereço manual - simular CEP baseado no estado
            $stateMapping = [
                'SP' => '01000000', 'RJ' => '20000000', 'MG' => '30000000',
                'RS' => '90000000', 'PR' => '80000000', 'SC' => '88000000',
                'GO' => '70000000', 'MT' => '78000000', 'MS' => '79000000',
                'DF' => '70000000', 'ES' => '29000000', 'BA' => '40000000',
                'PE' => '50000000', 'CE' => '60000000', 'PA' => '66000000',
                'AM' => '69000000', 'RO' => '76000000', 'AC' => '69900000'
            ];
            
            $state = strtoupper($manualAddress['state'] ?? '');
            $cep = $stateMapping[$state] ?? '01000000';
        }
        
        if ($cep && strlen($cep) === 8) {
            $shippingCost = $this->calculateShippingCost($cep, $subtotal);
        }
        
        // Verificar frete grátis
        $freeShippingMinimum = setting('free_shipping_minimum', 150);
        $freeShipping = $subtotal >= $freeShippingMinimum;
        
        return response()->json([
            'shipping' => $freeShipping ? 0 : $shippingCost,
            'free_shipping' => $freeShipping,
            'original_shipping' => $shippingCost
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'address_id' => 'nullable|exists:addresses,id',
            'manual_street' => 'nullable|string|max:255',
            'manual_number' => 'nullable|string|max:20',
            'manual_complement' => 'nullable|string|max:100',
            'manual_neighborhood' => 'nullable|string|max:100',
            'manual_city' => 'nullable|string|max:100',
            'manual_state' => 'nullable|string|size:2',
            'payment_method' => 'required|string|in:cash,credit_card,debit_card,pix,bank_transfer',
            'notes' => 'nullable|string|max:1000',
            'subtotal' => 'required|numeric|min:0',
            'shipping' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Buscar ou criar endereço de entrega
            $shippingAddressId = null;
            
            if ($validated['address_id']) {
                // Usar endereço existente
                $shippingAddressId = $validated['address_id'];
            } elseif ($validated['manual_street']) {
                // Criar endereço manual
                $shippingAddress = Address::create([
                    'customer_id' => $validated['customer_id'],
                    'type' => 'shipping',
                    'street' => $validated['manual_street'],
                    'number' => $validated['manual_number'],
                    'complement' => $validated['manual_complement'],
                    'neighborhood' => $validated['manual_neighborhood'],
                    'city' => $validated['manual_city'],
                    'state' => strtoupper($validated['manual_state']),
                    'postal_code' => '00000000', // CEP será definido posteriormente
                ]);
                
                $shippingAddressId = $shippingAddress->id;
            }

            // Criar pedido
            $orderNumber = 'PED-' . date('Y') . '-' . str_pad(Order::max('id') + 1, 6, '0', STR_PAD_LEFT);
            
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $validated['customer_id'],
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $shippingAddressId, // Usar mesmo endereço
                'subtotal' => $validated['subtotal'],
                'shipping_cost' => $validated['shipping'],
                'discount' => 0,
                'total' => $validated['total'],
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'notes' => $validated['notes'],
                'is_draft' => false,
            ]);

            // Gerar access token após criar o pedido (precisa do ID)
            $order->access_token = $order->generateAccessToken();
            $order->save();

            // Criar itens do pedido
            foreach ($validated['items'] as $productId => $itemData) {
                $product = Product::find($itemData['product_id']);
                
                // Verificar estoque
                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Estoque insuficiente para o produto {$product->name}");
                }
                
                // Criar item do pedido
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $itemData['price'],
                    'quantity' => $itemData['quantity'],
                    'subtotal' => $itemData['price'] * $itemData['quantity']
                ]);
                
                // Reduzir estoque
                $product->decrement('stock', $itemData['quantity']);
            }

            // Registrar transição de status no histórico
            \App\Models\OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => 'draft',
                'to_status' => 'pending',
                'reason' => 'Pedido criado via PDV',
                'changed_by' => 'admin',
            ]);

            DB::commit();

            // Disparar evento de pedido confirmado para notificação no Slack
            \Illuminate\Support\Facades\Log::info('Dispatching OrderConfirmed event for PDV order: ' . $order->id);
            OrderConfirmed::dispatch($order);

            // Enviar email de confirmação de pedido (assíncrono)
            Mail::queue(new OrderConfirmationMail($order));

            return response()->json([
                'success' => true,
                'message' => 'Venda criada com sucesso!',
                'order' => $order->load('customer', 'items.product'),
                'redirect_url' => route('admin.orders.show', $order->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar venda: ' . $e->getMessage()
            ], 422);
        }
    }

    private function calculateShippingCost($cep, $subtotal): float
    {
        // Verificar frete grátis
        $freeShippingMinimum = Setting::get('free_shipping_minimum', 0);
        
        if ($freeShippingMinimum > 0 && $subtotal >= $freeShippingMinimum) {
            return 0;
        }

        // Mapear CEP para estado
        $cepPrefix = substr($cep, 0, 2);
        $cepStateMap = [
            '01' => 'SP', '02' => 'SP', '03' => 'SP', '04' => 'SP', '05' => 'SP',
            '06' => 'SP', '07' => 'SP', '08' => 'SP', '09' => 'SP', '10' => 'SP',
            '11' => 'SP', '12' => 'SP', '13' => 'SP', '14' => 'SP', '15' => 'SP',
            '16' => 'SP', '17' => 'SP', '18' => 'SP', '19' => 'SP',
            '20' => 'RJ', '21' => 'RJ', '22' => 'RJ', '23' => 'RJ', '24' => 'RJ',
            '25' => 'RJ', '26' => 'RJ', '27' => 'RJ', '28' => 'RJ',
            '29' => 'ES',
            '30' => 'MG', '31' => 'MG', '32' => 'MG', '33' => 'MG', '34' => 'MG',
            '35' => 'MG', '36' => 'MG', '37' => 'MG', '38' => 'MG', '39' => 'MG',
            '40' => 'BA', '41' => 'BA', '42' => 'BA', '43' => 'BA', '44' => 'BA',
            '45' => 'BA', '46' => 'BA', '47' => 'BA', '48' => 'BA',
            '70' => 'DF', '71' => 'DF', '72' => 'GO',
            '80' => 'PR', '81' => 'PR', '82' => 'PR', '83' => 'PR', '84' => 'PR',
            '85' => 'PR', '86' => 'PR', '87' => 'PR',
            '88' => 'SC', '89' => 'SC',
            '90' => 'RS', '91' => 'RS', '92' => 'RS', '93' => 'RS', '94' => 'RS',
            '95' => 'RS', '96' => 'RS', '97' => 'RS', '98' => 'RS', '99' => 'RS',
        ];

        $stateCode = $cepStateMap[$cepPrefix] ?? 'XX';
        return ShippingRate::getRate($stateCode);
    }
}
