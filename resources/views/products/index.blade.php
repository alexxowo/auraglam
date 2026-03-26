@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    <!-- Sidebar -->
    @include('layouts.navigation')

    <!-- Main Content -->
    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-end mb-12">
                    <div>
                        <h1 class="display-lg text-[#303334] mb-2 font-black tracking-tight">Productos</h1>
                        <p class="body-md text-[#5d5f60]">Gestión de inventario y precios.</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('products.import') }}" class="flex items-center space-x-2 px-6 py-3 bg-white text-[#5d5f60] rounded-xl hover:bg-[#ffd9e2] hover:text-[#be004c] transition-all duration-300 shadow-sm group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="font-bold text-sm">Excel</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('products.create') }}" class="fab group" title="Nuevo Producto">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="absolute right-full mr-4 bg-[#303334] text-white px-3 py-1.5 rounded-xl text-xs opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none shadow-lg">
                        Nuevo Producto
                    </span>
                </a>

                @if(session('success'))
                    <div class="mb-8 p-4 bg-[#ffd9e2] text-[#be004c] rounded-xl body-md font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-6">
                    @forelse($products as $product)
                        <div class="card flex flex-col sm:flex-row items-start sm:items-center justify-between group hover:bg-[#f3f3f4] transition-colors cursor-default space-y-4 sm:space-y-0">
                            <div class="flex items-center space-x-4 sm:space-x-6">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 shrink-0 rounded-2xl bg-[#f3f3f4] flex items-center justify-center text-[#be004c] font-bold text-lg sm:text-xl">
                                    {{ substr($product->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('products.show', $product) }}" class="hover:text-[#be004c] transition-colors block">
                                        <h3 class="headline-md text-base sm:text-lg text-[#303334] mb-1 truncate">{{ $product->name }}</h3>
                                    </a>
                                    <div class="flex items-center space-x-3 sm:space-x-4">
                                        <span class="label-md uppercase tracking-widest text-[10px] sm:text-xs">Stock: {{ $product->stock }}</span>
                                        <span class="w-1 h-1 rounded-full bg-[#5d5f60]/20"></span>
                                        <span class="label-md uppercase tracking-widest text-[10px] sm:text-xs">Margen: ${{ number_format($product->margin, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between w-full sm:w-auto sm:space-x-12 sm:ml-4">
                                <div class="text-left sm:text-right">
                                    <span class="label-md block mb-0.5 sm:mb-1 uppercase tracking-widest text-[10px]">Precio Venta</span>
                                    <span class="headline-md text-lg sm:text-xl text-[#be004c] font-bold">${{ number_format($product->selling_price, 2) }}</span>
                                </div>
                                
                                <div class="flex items-center space-x-1 sm:space-x-2 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('products.edit', $product) }}" class="p-2 text-[#5d5f60] hover:text-[#be004c] hover:bg-white rounded-lg transition-colors text-xs sm:text-sm">
                                        Editar
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-[#5d5f60] hover:text-[#f97386] hover:bg-white rounded-lg transition-colors text-xs sm:text-sm">
                                            Borrar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card h-64 flex items-center justify-center border-dashed border-2 border-[#303334]/10 bg-transparent">
                            <p class="body-md text-[#5d5f60] italic">No hay productos registrados aún.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
