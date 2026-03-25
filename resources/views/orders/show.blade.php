@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-4xl mx-auto">
                <div class="mb-12 flex justify-between items-start">
                    <div>
                        <a href="{{ route('orders.index') }}" class="label-md uppercase tracking-widest hover:text-[#be004c] transition-colors mb-4 inline-block">
                            ← Volver a pedidos
                        </a>
                        <h1 class="display-lg text-[#303334] mb-2">{{ $order->document_number ?? 'Pedido #'.$order->id }}</h1>
                        <p class="body-md text-[#5d5f60]">Cliente: <span class="font-bold">{{ $order->customer_name }}</span> · {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    @php
                        $totalPaid = $order->payments->sum('amount');
                        $pending = $order->total_amount - $totalPaid;
                    @endphp

                    <div class="text-right">
                        @if($pending > 0)
                            <a href="{{ route('payments.create', ['order_id' => $order->id]) }}" class="btn-primary">
                                Registrar Pago
                            </a>
                        @else
                            <span class="px-6 py-2 bg-[#be004c] text-white rounded-xl headline-md text-sm uppercase tracking-widest">
                                Pagado Totalmente
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Items table -->
                    <div class="md:col-span-2 space-y-8">
                        <div class="card p-0 overflow-hidden">
                            <div class="p-6 border-b border-[#303334]/5">
                                <h3 class="label-md uppercase tracking-widest">Detalle de Productos</h3>
                            </div>
                            <table class="min-w-full divide-y divide-[#303334]/5">
                                <thead class="bg-[#f3f3f4]/30">
                                    <tr>
                                        <th class="px-6 py-3 text-left label-md text-[10px] uppercase">Producto</th>
                                        <th class="px-6 py-3 text-center label-md text-[10px] uppercase">Cant.</th>
                                        <th class="px-6 py-3 text-right label-md text-[10px] uppercase">Precio</th>
                                        <th class="px-6 py-3 text-right label-md text-[10px] uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#303334]/5">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 body-md text-[#303334]">{{ $item->product->name }}</td>
                                            <td class="px-6 py-4 text-center body-md text-[#5d5f60]">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 text-right body-md text-[#5d5f60]">${{ number_format($item->unit_price, 2) }}</td>
                                            <td class="px-6 py-4 text-right headline-md text-sm text-[#303334]">${{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="p-6 bg-[#303334] text-white flex justify-between items-center">
                                <span class="label-md uppercase tracking-widest opacity-60">Total Venta</span>
                                <span class="display-text text-3xl font-bold">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment summary -->
                    <div class="space-y-8">
                        <div class="card">
                            <h3 class="label-md uppercase tracking-widest mb-6">Estado de Cuenta</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="body-md text-[#5d5f60]">Total Pedido:</span>
                                    <span class="body-md font-medium">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="body-md text-[#5d5f60]">Pagado:</span>
                                    <span class="body-md font-medium text-[#be004c]">${{ number_format($totalPaid, 2) }}</span>
                                </div>
                                <div class="pt-4 border-t border-[#303334]/5 flex justify-between items-end">
                                    <span class="label-md uppercase tracking-widest">Pendiente</span>
                                    <span class="headline-md text-xl {{ $pending > 0 ? 'text-[#f97386]' : 'text-[#303334]' }}">
                                        ${{ number_format($pending, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <h3 class="label-md uppercase tracking-widest mb-4">Pagos Registrados</h3>
                            @forelse($order->payments as $payment)
                                <div class="py-3 border-b border-[#303334]/5 last:border-0">
                                    <div class="flex justify-between mb-1">
                                        <span class="body-sm font-medium text-[#303334]">{{ $payment->paymentMethod->name }}</span>
                                        <span class="body-sm font-bold text-[#be004c]">${{ number_format($payment->amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="label-md text-[10px]">{{ $payment->payment_date }}</span>
                                        <span class="label-md text-[10px] italic">{{ $payment->reference }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="body-sm text-[#5d5f60] italic">No hay pagos registrados.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
