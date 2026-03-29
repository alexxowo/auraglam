@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-6 sm:px-12">
            <div class="max-w-4xl mx-auto">
                <div class="mb-12 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-6 sm:space-y-0">
                    <div>
                        <a href="{{ route('orders.index') }}" class="label-md uppercase tracking-widest hover:text-[#be004c] transition-colors mb-4 inline-block">
                            ← Volver a pedidos
                        </a>
                        <h1 class="display-lg text-3xl sm:text-5xl text-[#303334] mb-2 font-black tracking-tight">{{ $order->document_number ?? 'Pedido #'.$order->id }}</h1>
                        <p class="body-md text-[#5d5f60]">Cliente: <span class="font-bold text-[#303334]">{{ $order->customer_name }}</span> · {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    @php
                        $totalPaid = $order->payments->sum('amount');
                        $pending = $order->total_amount - $totalPaid;
                    @endphp

                    <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('orders.pdf', $order) }}" target="_blank" class="flex-1 sm:flex-none flex items-center justify-center space-x-2 px-6 py-3 bg-[#f3f3f4] text-[#303334] rounded-xl headline-md text-sm uppercase tracking-widest hover:bg-[#e1e3e3] transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>PDF</span>
                        </a>

                        @if($pending > 0)
                            <a href="{{ route('payments.create', ['order_id' => $order->id]) }}" class="flex-1 sm:flex-none btn-primary px-6 py-3 text-center rounded-xl shadow-lg shadow-[#be004c]/10">
                                Registrar Pago
                            </a>
                        @else
                            <span class="flex-1 sm:flex-none px-6 py-3 bg-[#be004c] text-white rounded-xl headline-md text-sm uppercase tracking-widest text-center">
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
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="min-w-full divide-y divide-[#303334]/5">
                                    <thead class="bg-[#f3f3f4]/30">
                                        <tr>
                                            <th class="px-6 py-4 text-left label-md text-[10px] uppercase">Producto</th>
                                            <th class="px-6 py-4 text-center label-md text-[10px] uppercase whitespace-nowrap">Cant.</th>
                                            <th class="px-6 py-4 text-right label-md text-[10px] uppercase whitespace-nowrap">Precio</th>
                                            <th class="px-6 py-4 text-right label-md text-[10px] uppercase whitespace-nowrap">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#303334]/5">
                                        @foreach($order->items as $item)
                                            @php
                                                $subtotalBs = $order->exchangeRate ? $item->subtotal * $order->exchangeRate->value : null;
                                            @endphp
                                            <tr class="hover:bg-[#faf9f9] transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-col min-w-[200px] sm:min-w-0">
                                                        <span class="body-md text-[#303334] font-medium">{{ $item->product->name }}</span>
                                                        @if($item->variant)
                                                            <span class="text-[11px] text-[#970542] mt-0.5 font-bold uppercase tracking-wider">
                                                                {{ $item->variant->label }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center body-md text-[#5d5f60]">{{ $item->quantity }}</td>
                                                <td class="px-6 py-4 text-right body-md text-[#5d5f60] whitespace-nowrap">${{ number_format($item->unit_price, 2) }}</td>
                                                <td class="px-6 py-4 text-right">
                                                    <div class="flex flex-col items-end whitespace-nowrap">
                                                        @if($subtotalBs)
                                                            <span class="body-xs text-[#5d5f60] mb-0.5 opacity-60">BsS {{ number_format($subtotalBs, 2) }}</span>
                                                        @endif
                                                        <span class="headline-md text-sm text-[#303334] font-bold">${{ number_format($item->subtotal, 2) }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-6 bg-[#970542] text-white flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                                <div class="flex flex-col">
                                    <span class="label-md uppercase tracking-[0.2em] font-bold text-xs opacity-60">Total Venta</span>
                                    @if($order->exchangeRate)
                                        <span class="body-sm font-medium opacity-80">BsS {{ number_format($order->total_amount * $order->exchangeRate->value, 2) }}</span>
                                    @endif
                                </div>
                                <span class="display-text text-3xl sm:text-4xl font-black">${{ number_format($order->total_amount, 2) }}</span>
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
                                    <div class="flex flex-col items-end">
                                        @if($order->exchangeRate)
                                            <span class="body-xs text-[#5d5f60]">BsS {{ number_format($order->total_amount * $order->exchangeRate->value, 2) }}</span>
                                        @endif
                                        <span class="body-md font-medium">${{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                                <div class="flex justify-between">
                                    <span class="body-md text-[#5d5f60]">Pagado:</span>
                                    <div class="flex flex-col items-end">
                                        @if($order->exchangeRate)
                                            <span class="body-xs text-[#5d5f60]">BsS {{ number_format($totalPaid * $order->exchangeRate->value, 2) }}</span>
                                        @endif
                                        <span class="body-md font-medium text-[#be004c]">${{ number_format($totalPaid, 2) }}</span>
                                    </div>
                                </div>
                                <div class="pt-4 border-t border-[#303334]/5 flex justify-between items-end">
                                    <span class="label-md uppercase tracking-widest">Pendiente</span>
                                    <span class="headline-md text-xl {{ $pending > 0 ? 'text-[#f97386]' : 'text-[#303334]' }}">
                                        ${{ number_format($pending, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($order->exchangeRate)
                            <div class="card bg-[#f3f3f4]/20 border border-[#f3f3f4]">
                                <h3 class="label-md uppercase tracking-widest mb-4">Tasa de Operación</h3>
                                <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-sm space-x-2">
                                    <div class="w-10 h-10 rounded-lg bg-[#f3f3f4] shrink-0 flex items-center justify-center font-bold text-[#303334]">
                                        {{ $order->exchangeRate->currency }}
                                    </div>
                                    <div class="flex-1 px-2 overflow-hidden">
                                        <p class="text-[10px] label-md uppercase tracking-wider mb-0.5 opacity-60">Valor Unitario</p>
                                        <p class="body-sm font-bold text-[#303334] truncate">${{ number_format($order->exchangeRate->value, 2) }}</p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="text-[10px] text-[#5d5f60] uppercase mb-0.5 opacity-60 font-bold">Fuente</p>
                                        <p class="text-[10px] font-bold text-[#be004c] uppercase truncate">{{ $order->exchangeRate->source }}</p>
                                    </div>
                                </div>
                                <p class="text-[10px] text-[#5d5f60] mt-4 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Fijada el {{ $order->exchangeRate->last_update->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endif

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
