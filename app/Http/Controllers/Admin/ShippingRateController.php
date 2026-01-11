<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShippingRateController extends Controller
{
    public function index(): View
    {
        $shippingRates = ShippingRate::orderBy('state_code')->get();
        return view('admin.shipping-rates.index', compact('shippingRates'));
    }

    public function edit(ShippingRate $shippingRate): View
    {
        return view('admin.shipping-rates.edit', compact('shippingRate'));
    }

    public function update(ShippingRate $shippingRate, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'rate' => 'required|numeric|min:0|max:999.99',
            'is_active' => 'boolean',
        ]);

        $shippingRate->update([
            'rate' => $validated['rate'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Frete atualizado com sucesso!');
    }

    public function create(): View
    {
        return view('admin.shipping-rates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'state_code' => 'required|string|size:2|unique:shipping_rates,state_code',
            'state_name' => 'required|string|max:100',
            'rate' => 'required|numeric|min:0|max:999.99',
            'is_active' => 'boolean',
        ]);

        ShippingRate::create([
            'state_code' => strtoupper($validated['state_code']),
            'state_name' => $validated['state_name'],
            'rate' => $validated['rate'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Estado adicionado com sucesso!');
    }

    public function destroy(ShippingRate $shippingRate): RedirectResponse
    {
        $shippingRate->delete();
        
        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Estado removido com sucesso!');
    }

    public function toggleActive(ShippingRate $shippingRate): RedirectResponse
    {
        $shippingRate->update([
            'is_active' => !$shippingRate->is_active,
        ]);

        return redirect()->route('admin.shipping-rates.index')
            ->with('success', 'Status atualizado com sucesso!');
    }
}
