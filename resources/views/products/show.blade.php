@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-5xl mx-auto">
                <div class="mb-12">
                    <a href="{{ route('products.index') }}" class="label-md uppercase tracking-widest hover:text-[#be004c] transition-colors mb-4 inline-block">
                        ← Volver al listado
                    </a>
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="display-lg text-[#303334] mb-2">{{ $product->name }}</h1>
                            <p class="body-md text-[#5d5f60]">{{ $product->description ?: 'Sin descripción adicional.' }}</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="{{ route('products.edit', $product) }}" class="btn-primary bg-[#f3f3f4] text-[#303334] hover:bg-[#e1e3e3]">
                                Editar
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-primary bg-[#f97386]/10 text-[#f97386] hover:bg-[#f97386]/20">
                                    Borrar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Pricing Card -->
                    <div class="md:col-span-2 space-y-8">
                        <div class="card">
                            <h3 class="label-md uppercase tracking-widest mb-6 border-b border-[#303334]/5 pb-4">Análisis de Precios</h3>
                            <div class="grid grid-cols-2 gap-12">
                                <div>
                                    <span class="label-md block mb-1">Precio Compra</span>
                                    <span class="headline-md text-2xl text-[#5d5f60]">${{ number_format($product->purchase_price, 2) }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="label-md block mb-1">Precio Venta</span>
                                    <span class="headline-md text-3xl text-[#be004c]">${{ number_format($product->selling_price, 2) }}</span>
                                </div>
                            </div>
                            
                            <div class="mt-8 pt-8 border-t border-[#303334]/5 flex justify-between items-center">
                                <div>
                                    <span class="label-md block mb-1">Margen por Unidad</span>
                                    <span class="headline-md text-xl text-[#303334]">${{ number_format($product->margin, 2) }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="label-md block mb-1">Porcentaje de Ganancia</span>
                                    <span class="headline-md text-xl text-[#be004c]">
                                        {{ $product->purchase_price > 0 ? number_format(($product->margin / $product->purchase_price) * 100, 1) : '100' }}%
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-[#303334] text-white overflow-hidden relative">
                            <div class="relative z-10">
                                <h3 class="label-md uppercase tracking-widest mb-4 opacity-60 text-white">Proyección de Inventario</h3>
                                <div class="flex items-end space-x-4">
                                    <span class="display-text text-5xl font-bold">${{ number_format($product->stock * $product->selling_price, 2) }}</span>
                                    <span class="body-md opacity-60 mb-2">Valor Total en Venta</span>
                                </div>
                            </div>
                            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-[#be004c]/20 rounded-full blur-3xl"></div>
                        </div>
                    </div>

                    <!-- Stock Card -->
                    <div class="space-y-8">
                        <div class="card flex flex-col items-center justify-center text-center py-12">
                            <span class="label-md uppercase tracking-widest mb-4">Stock Disponible</span>
                            <span class="display-text text-6xl text-[#303334] mb-4">{{ $product->stock }}</span>
                            <div class="flex items-center space-x-2">
                                @if($product->stock <= 5)
                                    <span class="w-3 h-3 rounded-full bg-[#f97386] blur-[2px]"></span>
                                    <span class="label-md text-[#f97386]">Stock Bajo</span>
                                @else
                                    <span class="w-3 h-3 rounded-full bg-[#be004c]/40 blur-[2px]"></span>
                                    <span class="label-md text-[#be004c]">Saludable</span>
                                @endif
                            </div>
                        </div>

                        <div class="card">
                            <h3 class="label-md uppercase tracking-widest mb-4">Última Actividad</h3>
                            <p class="body-md text-[#5d5f60] italic">Creado el {{ $product->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
