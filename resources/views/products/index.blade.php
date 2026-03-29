@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    <!-- Sidebar -->
    @include('layouts.navigation')

    <!-- Main Content -->
    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 space-y-6 md:space-y-0">
                    <div>
                        <h1 class="display-lg text-[#303334] mb-2 font-black tracking-tight">Productos</h1>
                        <p class="body-md text-[#5d5f60]">Gestión de inventario y precios.</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 w-full md:w-auto">
                        <form action="{{ route('products.index') }}" method="GET" id="filterForm" class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 w-full">
                            <div class="relative w-full sm:w-72 group">
                                <input type="text" name="search" value="{{ $search ?? '' }}" 
                                       placeholder="Buscar..." 
                                       class="input-field pl-12! focus:ring-2 focus:ring-[#be004c]/20 transition-all">
                                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#5d5f60] group-focus-within:text-[#be004c] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>

                            <div class="w-full sm:w-48">
                                <select name="category_id" onchange="this.form.submit()" class="input-field appearance-none cursor-pointer">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if($search || $categoryId)
                                <a href="{{ route('products.index') }}" class="text-[#be004c] hover:underline label-md whitespace-nowrap">
                                    Limpiar
                                </a>
                            @endif
                        </form>

                        <div class="flex items-center space-x-3">
                            <a href="{{ route('products.import') }}" class="flex items-center space-x-2 px-5 py-3 bg-white text-[#5d5f60] rounded-xl hover:bg-[#ffd9e2]/10 hover:text-[#be004c] hover:shadow-md transition-all duration-300 group">
                                <svg class="w-4 h-4 text-[#be004c]/60 group-hover:text-[#be004c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12"></path>
                                </svg>
                                <span class="font-bold text-xs uppercase tracking-wider">Importar</span>
                            </a>
                            
                            <a href="{{ route('products.export') }}" class="flex items-center space-x-2 px-5 py-3 bg-white text-[#5d5f60] rounded-xl hover:bg-[#be004c] hover:text-white hover:shadow-lg transition-all duration-300 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                <span class="font-bold text-xs uppercase tracking-wider">Exportar</span>
                            </a>
                        </div>
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
                    <div class="mb-8 p-4 bg-[#ffd9e2] text-[#be004c] rounded-xl body-md font-medium animate-in fade-in slide-in-from-top-4 duration-300">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-6">
                    @forelse($products as $product)
                        <div class="card flex flex-col sm:flex-row items-start sm:items-center justify-between group hover:bg-[#f3f3f4] transition-colors cursor-default space-y-4 sm:space-y-0 relative overflow-hidden">
                            <div class="flex items-center space-x-4 sm:space-x-6">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 shrink-0 rounded-2xl bg-[#f3f3f4] flex items-center justify-center text-[#be004c] font-bold text-lg sm:text-xl relative z-10">
                                    {{ substr($product->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1 relative z-10">
                                    <div class="flex items-center space-x-3 mb-1">
                                        <a href="{{ route('products.show', $product) }}" class="hover:text-[#be004c] transition-colors block">
                                            <h3 class="headline-md text-base sm:text-lg text-[#303334] truncate">{{ $product->name }}</h3>
                                        </a>
                                        @if($product->category)
                                            <span class="bg-[#ffd9e2] text-[#be004c] text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-lg">
                                                {{ $product->category->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-3 sm:space-x-4">
                                        <span class="label-md uppercase tracking-widest text-[10px] sm:text-xs">Stock: {{ $product->total_stock }}</span>
                                        <span class="w-1 h-1 rounded-full bg-[#5d5f60]/20"></span>
                                        <span class="label-md uppercase tracking-widest text-[10px] sm:text-xs">Margen: ${{ number_format($product->margin, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between w-full sm:w-auto sm:space-x-12 sm:ml-4 relative z-10">
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

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
