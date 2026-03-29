@extends('layouts.base')

@section('body')
<div class="min-h-screen flex flex-col bg-[#faf9f9]">
    <!-- Public Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-[#303334]/5 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 sm:px-12 h-20 flex items-center justify-between">
            <a href="{{ route('catalog.index') }}" class="display-sm text-2xl text-[#be004c] font-black tracking-tight">
                Aura Glam
            </a>
            <!-- Optional: Link to admin/login if needed, but usually hidden from public -->
        </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto w-full px-6 sm:px-12 py-12">
        <div class="text-center mb-16">
            <h1 class="display-lg text-4xl sm:text-6xl text-[#303334] mb-4 font-black tracking-tight">Catálogo Exclusivo</h1>
            <p class="body-md text-[#5d5f60] max-w-2xl mx-auto text-lg">Descubre nuestra selección premium de productos, diseñados para resaltar tu belleza.</p>
        </div>

        <!-- Filters Section -->
        <div class="card mb-12 bg-white/50 backdrop-blur-sm border border-[#303334]/5 shadow-sm">
            <form action="{{ route('catalog.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
                <div class="relative w-full md:flex-1 group">
                    <input type="text" name="search" value="{{ $search ?? '' }}" 
                           placeholder="Buscar productos..." 
                           class="input-field pl-12! bg-white focus:ring-2 focus:ring-[#be004c]/20 transition-all shadow-sm">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#5d5f60] group-focus-within:text-[#be004c] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <div class="w-full md:w-64 shrink-0">
                    <select name="category_id" onchange="this.form.submit()" class="input-field bg-white shadow-sm cursor-pointer appearance-none">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($search || $categoryId)
                    <a href="{{ route('catalog.index') }}" class="w-full md:w-auto px-6 py-3 text-[#5d5f60] hover:text-[#be004c] hover:bg-[#ffd9e2]/30 rounded-xl transition-colors text-center font-medium">
                        Limpiar
                    </a>
                @endif
                <button type="submit" class="hidden">Buscar</button>
            </form>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($products as $product)
                @php
                    $isOutOfStock = $product->total_stock <= 0;
                    $cleanPhone = preg_replace('/[^0-9+]/', '', $whatsappNumber);
                    $message = urlencode("Hola, me interesa adquirir el producto: {$product->name}");
                    $whatsappUrl = "https://wa.me/{$cleanPhone}?text={$message}";
                @endphp
                <div class="card p-0 flex flex-col group hover:-translate-y-1 hover:shadow-2xl hover:shadow-[#be004c]/10 transition-all duration-300 border border-[#303334]/5 overflow-hidden bg-white {{ $isOutOfStock ? 'opacity-70' : '' }}">
                    <!-- Product Image -->
                    <div class="aspect-square bg-[#f3f3f4] w-full relative overflow-hidden flex items-center justify-center">
                        @if($product->image_path)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full opacity-20 bg-linear-to-br from-[#ffd9e2] to-transparent absolute inset-0"></div>
                            <svg class="w-16 h-16 text-[#5d5f60] absolute opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        @endif
                        
                        @if($isOutOfStock)
                            <div class="absolute inset-x-0 bottom-0 bg-[#303334]/80 backdrop-blur-md text-white text-center py-2 label-md uppercase tracking-widest font-bold text-xs">
                                Agotado
                            </div>
                        @else
                            @if($product->category)
                                <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full label-md text-[10px] uppercase tracking-wider text-[#303334] shadow-sm">
                                    {{ $product->category->name }}
                                </div>
                            @endif
                        @endif
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex-1">
                            <h3 class="headline-md text-lg text-[#303334] mb-2 font-bold leading-tight">{{ $product->name }}</h3>
                            <p class="body-sm text-[#5d5f60] line-clamp-2 mb-4">{{ $product->description ?: 'Producto exclusivo Aura Glam.' }}</p>
                        </div>
                        
                        <div class="mt-auto">
                            <div class="flex items-end justify-between mb-6">
                                <span class="display-sm text-2xl font-black text-[#be004c]">${{ number_format($product->selling_price, 2) }}</span>
                                @if(!$isOutOfStock)
                                    <span class="label-md text-[10px] uppercase tracking-wider opacity-60">Stock: {{ $product->total_stock }}</span>
                                @endif
                            </div>

                            @if($isOutOfStock)
                                <button disabled class="w-full py-3.5 bg-[#f3f3f4] text-[#5d5f60] rounded-xl font-bold uppercase tracking-wider text-sm cursor-not-allowed">
                                    Agotado
                                </button>
                            @else
                                <a href="{{ $whatsappUrl }}" target="_blank" class="w-full py-3.5 bg-[#be004c] text-white rounded-xl font-bold uppercase tracking-wider text-sm flex items-center justify-center space-x-2 hover:bg-[#970542] hover:shadow-lg hover:shadow-[#be004c]/30 transition-all active:scale-95 group/btn">
                                    <span>Comprar</span>
                                    <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center">
                    <svg class="w-16 h-16 text-[#5d5f60] opacity-20 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="headline-md text-2xl text-[#303334] mb-2 font-bold">No se encontraron productos</h3>
                    <p class="body-md text-[#5d5f60] mb-6">Prueba con otros términos de búsqueda o selecciona otra categoría.</p>
                    <a href="{{ route('catalog.index') }}" class="btn-primary inline-flex items-center space-x-2">
                        <span>Ver todo el catálogo</span>
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-16 pt-8 border-t border-[#303334]/5">
                {{ $products->links() }}
            </div>
        @endif
    </main>
    
    <!-- Public Footer -->
    <footer class="bg-white border-t border-[#303334]/5 mt-auto">
        <div class="max-w-7xl mx-auto px-6 sm:px-12 py-12 text-center">
            <span class="display-sm text-xl text-[#303334] font-black tracking-tight mb-4 block">Aura Glam</span>
            <p class="body-sm text-[#5d5f60]">© {{ date('Y') }} Aura Glam. Todos los derechos reservados.</p>
        </div>
    </footer>
</div>
@endsection
