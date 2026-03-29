@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-6 sm:px-12">
            <div class="max-w-6xl mx-auto">
                <header class="mb-12">
                    <a href="{{ route('products.show', $product) }}" class="label-md uppercase tracking-widest hover:text-[#be004c] transition-colors mb-4 inline-block">
                        ← Volver al producto
                    </a>
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end space-y-6 sm:space-y-0">
                        <div>
                            <h1 class="display-lg text-3xl sm:text-5xl text-[#303334] mb-2 font-black tracking-tight">Gestionar Variantes</h1>
                            <p class="body-md text-[#5d5f60] max-w-xl">Control de atributos y combinaciones para <strong>{{ $product->name }}</strong>.</p>
                        </div>
                        <form action="{{ route('products.variants.generate', $product) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="btn-primary flex items-center justify-center space-x-2 w-full sm:w-auto px-6 py-3 rounded-xl shadow-lg shadow-[#be004c]/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2-2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span class="text-sm font-bold uppercase tracking-wider">Generar Combinaciones</span>
                            </button>
                        </form>
                    </div>
                </header>

                @if(session('success'))
                    <div class="mb-8 p-4 bg-green-50 text-green-700 rounded-xl body-md font-medium border-l-4 border-green-500">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-8 p-4 bg-red-50 text-red-700 rounded-xl body-md font-medium border-l-4 border-red-500">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Attributes Column -->
                    <div class="lg:col-span-1 space-y-8">
                        <div class="card bg-white p-8 rounded-2xl shadow-sm">
                            <h3 class="label-md uppercase tracking-widest mb-6 border-b border-[#303334]/5 pb-4">Atributos (Ej: Color, Talla)</h3>
                            
                            <!-- Add Attribute Form -->
                            <form action="{{ route('products.attributes.store', $product) }}" method="POST" class="mb-8">
                                @csrf
                                <div class="flex space-x-2">
                                    <input type="text" name="name" placeholder="Nuevo atributo..." class="input-field flex-1" required>
                                    <button type="submit" class="p-3 bg-[#be004c] text-white rounded-xl hover:scale-105 transition-transform shadow-lg shadow-[#be004c]/20">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                            </form>

                            <!-- Attributes List -->
                            <div class="space-y-6">
                                @foreach($product->attributes as $attribute)
                                    <div class="p-4 bg-[#faf9f9] rounded-xl border border-[#303334]/5">
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="headline-md text-sm text-[#303334]">{{ $attribute->name }}</span>
                                            <form action="{{ route('attributes.destroy', $attribute) }}" method="POST" onsubmit="return confirm('¿Eliminar atributo y sus valores?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs text-[#f97386] hover:underline">Eliminar</button>
                                            </form>
                                        </div>

                                        <!-- Values List -->
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @foreach($attribute->values as $value)
                                                <span class="px-3 py-1 bg-[#ffd9e2]/30 text-[#be004c] rounded-full text-xs font-bold border border-[#be004c]/10">
                                                    {{ $value->value }}
                                                </span>
                                            @endforeach
                                        </div>

                                        <!-- Add Value Form -->
                                        <form action="{{ route('attributes.values.store', $attribute) }}" method="POST">
                                            @csrf
                                            <div class="flex space-x-1">
                                                <input type="text" name="value" placeholder="Nuevo valor..." class="input-field py-1 text-xs" required>
                                                <button type="submit" class="px-3 bg-white border border-[#303334]/10 rounded-lg hover:bg-[#303334] hover:text-white transition-colors">
                                                    +
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Variants Column -->
                    <div class="lg:col-span-2">
                        <div class="card bg-white p-0 rounded-2xl shadow-sm overflow-hidden">
                            <div class="p-8 border-b border-[#303334]/5">
                                <h3 class="label-md uppercase tracking-widest">Combinaciones Generadas</h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-[#f3f3f4]">
                                            <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs">Variante / SKU</th>
                                            <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs">Stock</th>
                                            <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs">Precio (Opcional)</th>
                                            <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#f3f3f4]">
                                        @forelse($product->variants as $variant)
                                            <tr class="hover:bg-[#faf9f9] transition-colors group">
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-col">
                                                        <span class="headline-md text-sm text-[#303334]">{{ $variant->label }}</span>
                                                        <span class="label-md text-[10px] text-[#5d5f60]">{{ $variant->sku }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <form id="update-{{ $variant->id }}" action="{{ route('variants.update', $variant) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="number" name="stock" value="{{ $variant->stock }}" class="input-field py-1 w-20 text-center" min="0">
                                                    </form>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="relative flex items-center">
                                                        <span class="absolute left-3 text-[#5d5f60] text-xs font-black select-none pointer-events-none">$</span>
                                                        <input type="number" step="0.01" name="price_override" form="update-{{ $variant->id }}" value="{{ $variant->price_override }}" class="input-field py-1 pl-8 w-28 text-right" placeholder="{{ number_format($product->selling_price, 2) }}">
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-right space-x-2">
                                                    <button type="submit" form="update-{{ $variant->id }}" class="p-2 text-[#be004c] hover:bg-[#be004c] hover:text-white rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                    <form action="{{ route('variants.destroy', $variant) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta variante?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="p-2 text-[#f97386] hover:bg-[#f97386] hover:text-white rounded-lg transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center">
                                                        <svg class="w-12 h-12 text-[#5d5f60] opacity-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V5M4 11v10l8 4m0-10l8 4m-8 4l8-4"></path></svg>
                                                        <span class="body-md text-[#5d5f60]">No hay variantes generadas aún.</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
