<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ExchangeRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Order::with(['payments', 'items.product', 'exchangeRate'])->latest();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->where('customer_name', 'like', '%'.$request->search.'%');
        }

        $orders = $query->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $products = Product::where('stock', '>', 0)->get();
        $latestRate = ExchangeRate::where('currency', 'EUR')->where('source', 'oficial')->latest('last_update')->first();

        return view('orders.create', compact('products', 'latestRate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $latestRate = ExchangeRate::where('currency', 'EUR')->where('source', 'oficial')->latest('last_update')->first();

            $today = date('dmY');
            $lastOrder = Order::where('document_number', 'like', $today.'%')
                ->orderBy('document_number', 'desc')
                ->lockForUpdate() // Avoid collisions
                ->first();

            $sequence = $lastOrder ? (intval(substr($lastOrder->document_number, 8)) + 1) : 1;
            $documentNumber = $today . str_pad($sequence, 8, '0', STR_PAD_LEFT);

            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'document_number' => $documentNumber,
                'total_amount' => 0,
                'status' => 'pending',
                'exchange_rate_id' => $latestRate ? $latestRate->id : null,
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para: {$product->name}");
                }

                $subtotal = $product->selling_price * $item['quantity'];
                
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $item['quantity']);
                $totalAmount += $subtotal;
            }

            $order->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Pedido creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View
    {
        $order->load(['items.product', 'payments.paymentMethod']);
        return view('orders.show', compact('order'));
    }

    /**
     * Get order details as JSON for AJAX modals.
     */
    public function getDetails(string $documentNumber): \Illuminate\Http\JsonResponse
    {
        $order = Order::with(['items.product', 'payments.paymentMethod'])
            ->where('document_number', $documentNumber)
            ->firstOrFail();

        return response()->json([
            'document_number' => $order->document_number,
            'customer_name' => $order->customer_name,
            'total_amount' => $order->total_amount,
            'items' => $order->items->map(fn($item) => [
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
            ]),
            'payments' => $order->payments->map(fn($payment) => [
                'date' => $payment->payment_date,
                'method' => $payment->paymentMethod->name,
                'amount' => $payment->amount,
            ]),
            'total_paid' => $order->payments->sum('amount'),
            'show_url' => route('orders.show', $order)
        ]);
    }
}
