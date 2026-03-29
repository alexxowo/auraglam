@extends('layouts.base')

@section('body')
<div class="min-h-screen flex flex-col bg-[#faf9f9] font-inter">
    <!-- Main Content -->
    <main class="flex-1 max-w-[1400px] mx-auto w-full px-6 py-6 sm:py-10">
        
        <!-- Top Navigation / Search Bar -->
        <div class="flex flex-col lg:flex-row gap-4 justify-between items-center mb-10">
            <!-- Search Form -->
            <form action="{{ route('catalog.index') }}" method="GET" class="w-full lg:flex-1 flex flex-col sm:flex-row gap-4 items-center">
                <div class="relative w-full lg:max-w-xl group">
                    <input type="text" name="search" value="{{ $search ?? '' }}" 
                           placeholder="Buscar belleza curada por nombre o marca..." 
                           class="w-full pl-12 pr-4 py-3 bg-[#f3f3f4] border-none rounded-full focus:ring-2 focus:ring-[#be004c]/20 transition-all text-[#303334] placeholder:text-[#5d5f60]/70 text-sm">
                    <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-[#5d5f60] group-focus-within:text-[#be004c] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <div class="flex gap-4 w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0 hide-scrollbar">
                    <select name="category_id" onchange="this.form.submit()" class="bg-[#f3f3f4] border-none rounded-full py-3 px-6 pr-10 text-sm text-[#303334] focus:ring-2 focus:ring-[#be004c]/20 transition-all appearance-none cursor-pointer whitespace-nowrap">
                        <option value="">Todo el Catálogo</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <button type="button" class="bg-[#ffd9e2] text-[#be004c] border-none rounded-full py-3 px-6 text-sm font-semibold flex items-center gap-2 whitespace-nowrap hover:bg-[#ffc2cf] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                        Nombre A-Z
                    </button>
                    <button type="submit" class="hidden">Buscar</button>
                    @if($search || $categoryId)
                        <a href="{{ route('catalog.index') }}" class="bg-white hover:bg-[#f3f3f4] text-[#5d5f60] border border-[#303334]/5 rounded-full py-3 px-6 text-sm font-semibold flex items-center gap-2 whitespace-nowrap transition-colors">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Hero Section -->
        <div class="relative w-full rounded-3xl overflow-hidden bg-linear-to-r from-[#edae8e] sm:from-[#fc306f] to-[#deb39b] sm:to-[#eebca3] mb-12 sm:mb-20 min-h-[300px] sm:min-h-[400px] flex items-center shadow-lg">
            <!-- Background Image overlay if available -->
            <div class="absolute inset-0 bg-linear-to-r from-[#fc306f]/90 via-[#fc306f]/40 to-transparent sm:w-2/3 pointer-events-none"></div>
            
            <div class="relative z-10 px-8 sm:px-16 py-12 max-w-2xl text-white">
                <span class="inline-block bg-[#be004c] text-white text-[10px] font-bold uppercase tracking-widest py-1.5 px-4 rounded-full mb-6">
                    Colecciones Exclusivas
                </span>
                <h1 class="font-manrope text-5xl sm:text-6xl text-white mb-6 font-black tracking-tight leading-tight">
                    La Colección Radiante Primavera '24
                </h1>
                <p class="text-lg text-white/90 mb-8 max-w-md font-medium leading-relaxed">
                    Esenciales curados para el brillo etéreo. Acceso directo a cuidado de piel premium desde nuestras boutiques principales.
                </p>
                <a href="#" class="inline-flex items-center gap-2 bg-[#be004c] hover:bg-[#970542] text-white font-bold py-3.5 px-8 rounded-full transition-all transform hover:scale-105 shadow-xl shadow-[#be004c]/30">
                    <span>Explorar Colección</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 gap-y-12 mb-20">
            @forelse($products as $product)
                @php
                    $isOutOfStock = $product->total_stock <= 0;
                    $cleanPhone = preg_replace('/[^0-9+]/', '', $whatsappNumber ?? '');
                    $message = urlencode("Hola, me interesa adquirir el producto: {$product->name}");
                    $whatsappUrl = "https://wa.me/{$cleanPhone}?text={$message}";
                @endphp
                <div class="flex flex-col group {{ $isOutOfStock ? 'opacity-70' : '' }}">
                    <!-- Product Image Container -->
                    <div class="aspect-4/5 sm:aspect-square bg-[#303334]/5 rounded-4xl w-full relative overflow-hidden mb-5 flex items-center justify-center">
                        @if($product->image_path)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-linear-to-br from-[#ffd9e2]/80 to-[#f3f3f4] absolute inset-0"></div>
                            <svg class="w-16 h-16 text-[#5d5f60] absolute inset-0 m-auto opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        @endif
                        
                        <!-- Stock Badge -->
                        @if($isOutOfStock)
                            <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-md text-[#5d5f60] font-bold py-1.5 px-4 rounded-full text-[11px] uppercase tracking-wide shadow-sm">
                                Agotado
                            </div>
                        @else
                            <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-md text-[#22c55e] font-bold py-1.5 px-4 rounded-full text-[11px] uppercase tracking-wide shadow-sm flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#22c55e]"></span>
                                En Stock
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="flex flex-col flex-1 px-1">
                        <!-- Category Row -->
                        <div class="flex justify-between items-start mb-1 text-[11px] font-bold uppercase tracking-widest">
                            <span class="text-[#be004c]">
                                {{ $product->category ? $product->category->name : 'Categoría' }}
                            </span>
                        </div>
                        
                        <!-- Name and Price -->
                        <div class="flex justify-between items-start mb-1 gap-2">
                            <h3 class="font-manrope text-lg text-[#303334] font-black leading-tight flex-1">{{ $product->name }}</h3>
                            <span class="text-lg font-manrope font-bold text-[#303334]">${{ number_format($product->selling_price, 2) }}</span>
                        </div>
                        
                        <p class="text-sm text-[#5d5f60] truncate mb-5">{{ $product->description ?: 'Producto Exclusivo' }}</p>

                        <!-- Actions -->
                        <div class="mt-auto">
                            @if($isOutOfStock)
                                <button disabled class="w-full py-3 bg-[#f3f3f4] text-[#5d5f60] rounded-xl font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                    Agotado
                                </button>
                            @else
                                <a href="{{ $whatsappUrl }}" target="_blank" class="w-full py-3 bg-[#ffd9e2] text-[#be004c] rounded-xl font-bold text-[14px] flex items-center justify-center gap-2 hover:bg-[#ffc2cf] transition-colors focus:ring-4 focus:ring-[#ffd9e2]/80">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                                    </svg>
                                    Pedir por WhatsApp
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
                    <h3 class="font-manrope text-2xl text-[#303334] mb-2 font-bold">No se encontraron productos</h3>
                    <p class="text-[#5d5f60] mb-6">Prueba con otros términos de búsqueda o selecciona otra categoría.</p>
                    <a href="{{ route('catalog.index') }}" class="bg-[#be004c] text-white px-6 py-3 rounded-full font-bold">
                        Ver todo el catálogo
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mb-20">
                {{ $products->links() }}
            </div>
        @endif

        <!-- Bottom CTA -->
        <div class="bg-[#f3f3f4] rounded-[2.5rem] py-16 px-6 sm:px-12 text-center max-w-4xl mx-auto mb-10">
            <h2 class="font-manrope text-3xl sm:text-4xl text-[#303334] font-black mb-4 tracking-tight">Mantente Brillando</h2>
            <p class="text-[#5d5f60] mb-8 max-w-md mx-auto text-[15px] leading-relaxed">Únete a nuestro círculo íntimo para acceso exclusivo anticipado a las ediciones de temporada y tips de belleza.</p>
            
            <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto justify-center">
                <input type="email" placeholder="Dirección de correo electrónico" class="w-full sm:flex-1 py-4 px-6 rounded-full border-none shadow-sm focus:ring-2 focus:ring-[#be004c]/20 text-sm">
                <button type="submit" class="bg-[#be004c] hover:bg-[#970542] text-white font-bold py-4 px-8 rounded-full shadow-lg shadow-[#be004c]/30 transition-all active:scale-95 whitespace-nowrap text-sm">
                    Suscribirse
                </button>
            </form>
        </div>
    </main>
</div>

<!-- Floating WhatsApp Button (Global) -->
<a href="https://wa.me/{{ preg_replace('/[^0-9+]/', '', $whatsappNumber ?? '') }}" target="_blank" class="fixed bottom-6 right-6 z-50 bg-[#25D366] text-white p-3.5 rounded-full shadow-[0_8px_30px_rgb(37,211,102,0.4)] hover:scale-110 transition-transform flex items-center justify-center">
    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
    </svg>
</a>
@endsection
