<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashBook;
use App\Models\PaymentMethod;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class CashBookController extends Controller
{
    /**
     * Display the cash book
     */
    public function index(Request $request): View
    {
        $query = CashBook::with(['order.customer', 'paymentMethod']);

        // Filtros
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('payment_method_id')) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        if ($request->filled('start_date')) {
            $query->where('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('transaction_date', '<=', $request->end_date);
        }

        // Ordenação (mais recentes primeiro)
        $query->orderBy('created_at', 'desc')
              ->orderBy('id', 'desc');

        $entries = $query->paginate(20)->withQueryString();

        // Totalizadores
        $totalCredits = CashBook::when($request->filled('start_date'), function ($q) use ($request) {
                return $q->where('transaction_date', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                return $q->where('transaction_date', '<=', $request->end_date);
            })
            ->where('type', 'credit')
            ->sum('amount');

        $totalDebits = CashBook::when($request->filled('start_date'), function ($q) use ($request) {
                return $q->where('transaction_date', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                return $q->where('transaction_date', '<=', $request->end_date);
            })
            ->where('type', 'debit')
            ->sum('amount');

        $netAmount = $totalCredits - $totalDebits;

        $paymentMethods = PaymentMethod::active()->orderBy('name')->get();
        
        $categories = CashBook::distinct('category')->pluck('category')->sort();

        return view('admin.cash-book.index', compact(
            'entries', 
            'totalCredits', 
            'totalDebits', 
            'netAmount',
            'paymentMethods',
            'categories'
        ));
    }

    /**
     * Show the form for creating a new entry
     */
    public function create(): View
    {
        $paymentMethods = PaymentMethod::active()->orderBy('name')->get();
        $orders = Order::where('is_draft', false)
                      ->orderBy('created_at', 'desc')
                      ->take(50)
                      ->get();

        return view('admin.cash-book.create', compact('paymentMethods', 'orders'));
    }

    /**
     * Store a newly created entry
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'type' => 'required|in:credit,debit',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'fee_amount' => 'nullable|numeric|min:0',
            'description' => 'required|string|max:500',
            'transaction_date' => 'required|date',
            'settlement_date' => 'nullable|date|after_or_equal:transaction_date',
            'reference' => 'nullable|string|max:255',
        ]);

        // Calcular valor líquido
        $feeAmount = $validated['fee_amount'] ?? 0;
        $netAmount = $validated['type'] === 'credit' ? 
            $validated['amount'] - $feeAmount : 
            -($validated['amount']);

        $validated['net_amount'] = $netAmount;

        CashBook::recordEntry($validated);

        return redirect()->route('admin.cash-book.index')
            ->with('success', 'Lançamento registrado com sucesso!');
    }

    /**
     * Display the specified entry
     */
    public function show(CashBook $cashBook): View
    {
        $cashBook->load(['order.customer', 'paymentMethod']);
        
        return view('admin.cash-book.show', compact('cashBook'));
    }

    /**
     * Show the form for editing the specified entry
     */
    public function edit(CashBook $cashBook): View
    {
        $paymentMethods = PaymentMethod::active()->orderBy('name')->get();
        $orders = Order::where('is_draft', false)
                      ->orderBy('created_at', 'desc')
                      ->take(50)
                      ->get();

        return view('admin.cash-book.edit', compact('cashBook', 'paymentMethods', 'orders'));
    }

    /**
     * Update the specified entry
     */
    public function update(Request $request, CashBook $cashBook): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'type' => 'required|in:credit,debit',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'fee_amount' => 'nullable|numeric|min:0',
            'description' => 'required|string|max:500',
            'transaction_date' => 'required|date',
            'settlement_date' => 'nullable|date|after_or_equal:transaction_date',
            'reference' => 'nullable|string|max:255',
        ]);

        // Calcular valor líquido
        $feeAmount = $validated['fee_amount'] ?? 0;
        $netAmount = $validated['type'] === 'credit' ? 
            $validated['amount'] - $feeAmount : 
            -($validated['amount']);

        $validated['net_amount'] = $netAmount;

        $cashBook->update($validated);

        return redirect()->route('admin.cash-book.index')
            ->with('success', 'Lançamento atualizado com sucesso!');
    }

    /**
     * Remove the specified entry
     */
    public function destroy(CashBook $cashBook): RedirectResponse
    {
        $cashBook->delete();

        return redirect()->route('admin.cash-book.index')
            ->with('success', 'Lançamento removido com sucesso!');
    }

    /**
     * Generate reports
     */
    public function reports(Request $request): View
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Resumo por categoria
        $categoryReport = CashBook::selectRaw('
                category,
                type,
                COUNT(*) as total_transactions,
                SUM(amount) as total_amount,
                AVG(amount) as avg_amount
            ')
            ->byPeriod($startDate, $endDate)
            ->groupBy('category', 'type')
            ->orderBy('category')
            ->get();

        // Resumo por forma de pagamento
        $paymentMethodReport = CashBook::selectRaw('
                payment_method_id,
                COUNT(*) as total_transactions,
                SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as total_credits,
                SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as total_debits,
                SUM(fee_amount) as total_fees
            ')
            ->with('paymentMethod')
            ->byPeriod($startDate, $endDate)
            ->whereNotNull('payment_method_id')
            ->groupBy('payment_method_id')
            ->get();

        // Evolução diária
        $dailyEvolution = CashBook::selectRaw('
                transaction_date,
                SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as daily_credits,
                SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as daily_debits,
                SUM(net_amount) as daily_net
            ')
            ->byPeriod($startDate, $endDate)
            ->groupBy('transaction_date')
            ->orderBy('transaction_date')
            ->get();

        return view('admin.cash-book.reports', compact(
            'categoryReport',
            'paymentMethodReport', 
            'dailyEvolution',
            'startDate',
            'endDate'
        ));
    }
}
