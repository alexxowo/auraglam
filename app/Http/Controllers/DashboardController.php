<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $kpis = [
            'total_payments' => Payment::sum('amount'),
            'pending_balance' => Order::sum('total_amount') - Payment::sum('amount'),
            'orders_to_pay_count' => Order::whereIn('status', ['pending', 'partially_paid'])->count(),
            'orders_paid_count' => Order::where('status', 'paid')->count(),
            'inventory_stats' => [
                'total_items' => Product::count(),
                'total_value' => Product::sum(DB::raw('stock * purchase_price')),
                'low_stock_count' => Product::where('stock', '<', 5)->count(),
            ],
            'rates' => [
                'usd' => ExchangeRate::where('currency', 'USD')->where('source', 'oficial')->latest('last_update')->first(),
                'eur' => ExchangeRate::where('currency', 'EUR')->where('source', 'oficial')->latest('last_update')->first(),
            ],
        ];

        return view('dashboard', compact('kpis'));
    }

    public function chartData(Request $request): JsonResponse
    {
        $from = $request->get('from', now()->subDays(6)->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $sales = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total')
        )
            ->whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($sales);
    }
}
