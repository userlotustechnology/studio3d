<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $paymentMethods = PaymentMethod::orderBy('is_active', 'desc')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.payment-methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods',
            'description' => 'nullable|string',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'fee_fixed' => 'required|numeric|min:0',
            'settlement_days' => 'required|integer|min:0|max:365',
            'is_active' => 'boolean',
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Forma de pagamento criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod): View
    {
        $paymentMethod->loadCount('orders', 'cashBookEntries');
        
        return view('admin.payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod): View
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:payment_methods,code,' . $paymentMethod->id,
            'description' => 'nullable|string',
            'fee_percentage' => 'required|numeric|min:0|max:100',
            'fee_fixed' => 'required|numeric|min:0',
            'settlement_days' => 'required|integer|min:0|max:365',
            'is_active' => 'boolean',
        ]);

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Forma de pagamento atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        // Verificar se existem pedidos ou movimentações vinculadas
        if ($paymentMethod->orders()->exists() || $paymentMethod->cashBookEntries()->exists()) {
            return redirect()->route('admin.payment-methods.index')
                ->with('error', 'Não é possível excluir uma forma de pagamento que possui pedidos ou movimentações vinculadas.');
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Forma de pagamento removida com sucesso!');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active
        ]);

        $status = $paymentMethod->is_active ? 'ativada' : 'desativada';
        
        return redirect()->route('admin.payment-methods.index')
            ->with('success', "Forma de pagamento {$status} com sucesso!");
    }
}
