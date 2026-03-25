@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <!-- Main Content -->
    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-7xl mx-auto">
                <h1 class="display-lg text-[#303334] mb-2">Dashboard</h1>
                <p class="body-md text-[#5d5f60] mb-8">Bienvenido, {{ auth()->user()->name }}. Hoy es un buen día para vender.</p>

                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="card aspect-video flex flex-col justify-end">
                        <span class="label-md uppercase tracking-widest mb-2">Ventas Hoy</span>
                        <span class="display-text headline-md text-[#be004c]">$0.00</span>
                    </div>
                    <div class="card aspect-video flex flex-col justify-end">
                        <span class="label-md uppercase tracking-widest mb-2">Pedidos Pendientes</span>
                        <span class="display-text headline-md text-[#303334]">0</span>
                    </div>
                    <div class="card aspect-video flex flex-col justify-end">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-[#f97386] blur-[2px]"></span>
                            <span class="label-md uppercase tracking-widest">Estado Sistema</span>
                        </div>
                        <span class="display-text headline-md text-[#303334]">Activo</span>
                    </div>
                </div>

                <div class="mt-12">
                    <div class="card h-64 flex items-center justify-center border-dashed border-2 border-[#303334]/10 bg-transparent">
                        <p class="body-md text-[#5d5f60] italic">Comienza agregando productos a tu inventario...</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
