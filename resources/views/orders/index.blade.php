@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-end mb-12">
                    <div>
                        <h1 class="display-lg text-[#303334] mb-2 font-black tracking-tight">Pedidos de Venta</h1>
                        <p class="body-md text-[#5d5f60]">Gestiona y consulta el historial de ventas.</p>
                    </div>
                </div>

                <a href="{{ route('orders.create') }}" class="fab group" title="Nuevo Pedido">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="absolute right-full mr-4 bg-[#303334] text-white px-3 py-1.5 rounded-xl text-xs opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none shadow-lg">
                        Nuevo Pedido
                    </span>
                </a>

                <!-- Filters -->
                <div class="card mb-8">
                    <form action="{{ route('orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        <div class="md:col-span-2">
                            <label for="search" class="label-md block mb-2 uppercase tracking-wider">Buscar Cliente</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   class="input-field" placeholder="Nombre del cliente...">
                        </div>
                        <div>
                            <label for="status" class="label-md block mb-2 uppercase tracking-wider">Estado</label>
                            <select name="status" id="status" class="select2 input-field" data-placeholder="Filtrar por estado">
                                <option></option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Parcial</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                            </select>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="btn-primary flex-1">Filtrar</button>
                            <a href="{{ route('orders.index') }}" class="btn-secondary flex items-center justify-center px-4 rounded-xl border border-[#303334]/10">
                                Limpiar
                            </a>
                        </div>
                    </form>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-4 bg-[#ffd9e2] text-[#be004c] rounded-xl body-md font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="min-w-full divide-y divide-[#303334]/5">
                        <thead class="bg-[#f3f3f4]/50">
                            <tr>
                                <th class="px-6 py-4 text-left label-md uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left label-md uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-4 text-left label-md uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-4 text-left label-md uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-4 text-left label-md uppercase tracking-wider">Items</th>
                                <th class="px-6 py-4 text-right label-md uppercase tracking-wider">Total</th>
                                <th class="px-6 py-4 text-right label-md uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#303334]/5">
                            @forelse($orders as $order)
                                <tr class="hover:bg-[#f3f3f4]/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap body-md text-[#5d5f60]">{{ $order->document_number ?? '#'.$order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap headline-md text-[#303334]">{{ $order->customer_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap body-md text-[#5d5f60]">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $totalPaid = $order->payments->sum('amount');
                                            $status = 'pending';
                                            if ($totalPaid >= $order->total_amount && $order->total_amount > 0) {
                                                $status = 'paid';
                                            } elseif ($totalPaid > 0) {
                                                $status = 'partial';
                                            }
                                            
                                            $statusColors = [
                                                'pending' => 'bg-[#f3f3f4] text-[#5d5f60]',
                                                'partial' => 'bg-[#ffd9e2] text-[#be004c]',
                                                'paid' => 'bg-[#be004c] text-white'
                                            ];
                                            
                                            $statusLabels = [
                                                'pending' => 'Pendiente',
                                                'partial' => 'Pago Parcial',
                                                'paid' => 'Pagado'
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$status] }}">
                                            {{ $statusLabels[$status] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap body-md text-[#5d5f60]">
                                        {{ $order->items->count() }} prod.
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right headline-md text-[#303334]">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('orders.show', $order) }}" class="p-2 text-[#5d5f60] hover:text-[#be004c] hover:bg-white rounded-lg transition-colors">
                                                Ver
                                            </a>
                                            @if($status !== 'paid')
                                                <a href="{{ route('payments.create', ['order_id' => $order->id]) }}" class="p-2 text-[#be004c] font-bold text-xs hover:bg-white rounded-lg transition-colors">
                                                    Pagar
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center body-md text-[#5d5f60] italic">
                                        No se han encontrado pedidos.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
