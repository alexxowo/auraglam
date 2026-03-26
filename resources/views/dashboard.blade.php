@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <!-- Main Content -->
    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-7xl mx-auto">
                <header class="mb-8">
                    <h1 class="display-lg text-[#303334] mb-2">Dashboard</h1>
                    <p class="body-md text-[#5d5f60]">Bienvenido back, {{ auth()->user()->name }}. Monitoriza el pulso de tu negocio en tiempo real.</p>
                </header>

                <!-- Exchange Rates -->
                <div class="flex flex-wrap gap-4 mb-12">
                    @foreach(['usd', 'eur'] as $curr)
                        @if($rate = $kpis['rates'][$curr])
                            <div class="card py-3 px-6 flex items-center space-x-4 bg-white rounded-2xl shadow-sm border border-[#f3f3f4]">
                                <div class="w-10 h-10 rounded-xl bg-[#f3f3f4] flex items-center justify-center font-bold text-[#303334]">
                                    {{ strtoupper($curr) }}
                                </div>
                                <div>
                                    <p class="text-xs label-md uppercase tracking-wider mb-0.5">Tasa {{ $rate->source }}</p>
                                    <p class="body-md font-bold text-[#303334]">{{ number_format($rate->value, 2) }}BsS x €
                                    </p>
                                </div>
                                <div class="pl-4 border-l border-[#f3f3f4]">
                                    <p class="text-[10px] text-[#5d5f60] uppercase tracking-tighter">Última actualización</p>
                                    <p class="text-[10px] font-medium text-[#5d5f60]">{{ $rate->last_update->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- KPI Blocks -->
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4 mb-12">
                    <div class="card p-8 flex flex-col justify-between bg-white rounded-xl shadow-sm hover:scale-[1.02] transition-transform duration-300">
                        <span class="label-md uppercase tracking-widest text-[#5d5f60] mb-4">Pagos Realizados</span>
                        <span class="headline-md text-[#be004c] font-bold">${{ number_format($kpis['total_payments'], 2) }}</span>
                    </div>

                    <div class="card p-8 flex flex-col justify-between bg-white rounded-xl shadow-sm hover:scale-[1.02] transition-transform duration-300">
                        <span class="label-md uppercase tracking-widest text-[#5d5f60] mb-4">Pagos Pendientes</span>
                        <span class="headline-md text-[#303334] font-bold">${{ number_format($kpis['pending_balance'], 2) }}</span>
                    </div>

                    <div class="card p-8 flex flex-col justify-between bg-white rounded-xl shadow-sm hover:scale-[1.02] transition-transform duration-300">
                        <span class="label-md uppercase tracking-widest text-[#5d5f60] mb-4">Pedidos Pendientes</span>
                        <div class="flex items-center justify-between">
                            <span class="headline-md text-[#303334] font-bold">{{ $kpis['orders_to_pay_count'] }}</span>
                            <span class="text-xs px-2 py-1 bg-[#ffd9e2] text-[#be004c] rounded-full font-medium">Por Pagar</span>
                        </div>
                    </div>

                    <div class="card p-8 flex flex-col justify-between bg-white rounded-xl shadow-sm hover:scale-[1.02] transition-transform duration-300">
                        <span class="label-md uppercase tracking-widest text-[#5d5f60] mb-4">Pedidos Pagados</span>
                        <div class="flex items-center justify-between">
                            <span class="headline-md text-[#303334] font-bold">{{ $kpis['orders_paid_count'] }}</span>
                            <span class="text-xs px-2 py-1 bg-green-50 text-green-700 rounded-full font-medium">Completados</span>
                        </div>
                    </div>
                </div>

                <!-- Sales Chart Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                    <div class="lg:col-span-2 card p-10 bg-white rounded-xl shadow-sm">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                            <h2 class="headline-md text-[#303334]">Ventas Recientes</h2>
                            <div class="flex items-center space-x-4">
                                <div class="flex flex-col">
                                    <label class="label-md text-xs mb-1">Desde</label>
                                    <input type="date" id="chart-from" value="{{ now()->subDays(6)->format('Y-m-d') }}" class="text-sm bg-[#f3f3f4] border-none rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-[#be004c]/20">
                                </div>
                                <div class="flex flex-col">
                                    <label class="label-md text-xs mb-1">Hasta</label>
                                    <input type="date" id="chart-to" value="{{ now()->format('Y-m-d') }}" class="text-sm bg-[#f3f3f4] border-none rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-[#be004c]/20">
                                </div>
                            </div>
                        </div>
                        <div class="h-80 relative">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <!-- Inventory Statistics -->
                    <div class="card p-10 bg-white rounded-xl shadow-sm flex flex-col">
                        <h2 class="headline-md text-[#303334] mb-8">Estado de Inventario</h2>
                        <div class="space-y-8 flex-1">
                            <div class="flex justify-between items-end border-b border-[#f3f3f4] pb-4">
                                <div>
                                    <h4 class="label-md text-[#5d5f60] mb-1">Valor Total</h4>
                                    <p class="headline-md text-[#303334] font-bold">${{ number_format($kpis['inventory_stats']['total_value'], 2) }}</p>
                                </div>
                                <span class="text-xs text-[#5d5f60]">Inversión</span>
                            </div>
                            <div class="flex justify-between items-end border-b border-[#f3f3f4] pb-4">
                                <div>
                                    <h4 class="label-md text-[#5d5f60] mb-1">Productos Únicos</h4>
                                    <p class="headline-md text-[#303334] font-bold">{{ $kpis['inventory_stats']['total_items'] }}</p>
                                </div>
                                <span class="text-xs text-[#5d5f60]">SKUs</span>
                            </div>
                            <div class="flex items-center space-x-4 p-4 bg-[#ffd9e2]/30 rounded-xl">
                                <div class="relative">
                                    <div class="w-4 h-4 rounded-full bg-[#f97386] {{ $kpis['inventory_stats']['low_stock_count'] > 0 ? 'animate-pulse' : '' }}"></div>
                                    <div class="absolute inset-0 w-4 h-4 rounded-full bg-[#f97386] blur-xs"></div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="label-md text-[#be004c] font-bold">Stock Bajo</h4>
                                    <p class="body-md text-[#be004c]">{{ $kpis['inventory_stats']['low_stock_count'] }} productos necesitan atención.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    let salesChart;

    const fromInput = document.getElementById('chart-from');
    const toInput = document.getElementById('chart-to');

    async function fetchChartData() {
        const from = fromInput.value;
        const to = toInput.value;
        
        try {
            const response = await fetch(`{{ route('dashboard.chart-data') }}?from=${from}&to=${to}`);
            const data = await response.json();
            
            updateChart(data);
        } catch (error) {
            console.error('Error fetching chart data:', error);
        }
    }

    function updateChart(data) {
        const labels = data.map(item => item.date);
        const values = data.map(item => item.total);

        if (salesChart) {
            salesChart.destroy();
        }

        salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas ($)',
                    data: values,
                    borderColor: '#be004c',
                    backgroundColor: 'rgba(190, 0, 76, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#be004c',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#303334',
                        padding: 12,
                        titleFont: { size: 14, family: 'Inter' },
                        bodyFont: { size: 14, family: 'Inter' },
                        callbacks: {
                            label: function(context) {
                                return ' $' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { family: 'Inter', size: 12 },
                            color: '#5d5f60'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f3f3f4'
                        },
                        ticks: {
                            font: { family: 'Inter', size: 12 },
                            color: '#5d5f60',
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
    }

    fromInput.addEventListener('change', fetchChartData);
    toInput.addEventListener('change', fetchChartData);

    // Initial load
    fetchChartData();
});
</script>
@endsection
