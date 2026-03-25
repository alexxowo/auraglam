<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $payments = Payment::with(['order', 'paymentMethod'])->latest()->get();

        return view('treasury.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $orders = Order::latest()->get();
        $methods = PaymentMethod::all();
        $selectedOrderId = $request->query('order_id');

        return view('treasury.payments.create', compact('orders', 'methods', 'selectedOrderId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'reference' => 'nullable|string|max:255',
        ]);

        Payment::create($validated);

        // Update order status if necessary (logic could be more complex, but for now just link)
        
        return redirect()->route('payments.index')
            ->with('success', 'Pago registrado exitosamente.');
    }
}
