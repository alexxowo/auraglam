@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-3xl mx-auto">
                <div class="mb-12">
                    <a href="{{ route('products.index') }}" class="label-md uppercase tracking-widest hover:text-[#be004c] transition-colors mb-4 inline-block">
                        ← Volver al listado
                    </a>
                    <h1 class="display-lg text-[#303334] mb-2">{{ isset($product) ? 'Editar Producto' : 'Nuevo Producto' }}</h1>
                    <p class="body-md text-[#5d5f60]">Completa los detalles del producto para el inventario.</p>
                </div>

                <div class="card">
                    <form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @if(isset($product))
                            @method('PUT')
                        @endif

                        <div class="space-y-6">
                            <!-- Image Upload Section -->
                            <div>
                                <label class="label-md block mb-2 uppercase tracking-wider">Imagen Principal</label>
                                <div class="mt-2 flex items-center gap-6">
                                    <div id="image-preview" class="w-32 h-32 rounded-2xl bg-[#f3f3f4] border border-[#303334]/5 overflow-hidden flex items-center justify-center relative group">
                                        @if(isset($product) && $product->image_path)
                                            <img src="{{ $product->image_url }}" alt="Preview" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-12 h-12 text-[#5d5f60] opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-[#5d5f60]
                                            file:mr-4 file:py-2.5 file:px-6
                                            file:rounded-xl file:border-0
                                            file:text-sm file:font-bold file:uppercase file:tracking-wider
                                            file:bg-[#be004c]/10 file:text-[#be004c]
                                            hover:file:bg-[#be004c]/20 file:transition-colors file:cursor-pointer cursor-pointer">
                                        <p class="mt-2 label-md text-[10px] opacity-60">PNG, JPG o WEBP (Máx. 5MB). Relación recomendada 1:1.</p>
                                        @error('image') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="name" class="label-md block mb-2 uppercase tracking-wider">Nombre del Producto</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required
                                       class="input-field" placeholder="Ej: Vestido de Gala Seda">
                                @error('name') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="description" class="label-md block mb-2 uppercase tracking-wider">Descripción (Opcional)</label>
                                <textarea name="description" id="description" rows="3" 
                                          class="input-field" placeholder="Detalles sobre el material, color, etc.">{{ old('description', $product->description ?? '') }}</textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="purchase_price" class="label-md block mb-2 uppercase tracking-wider">Precio Compra</label>
                                    <div class="relative flex items-center">
                                        <span class="absolute left-4 text-[#5d5f60] text-xs font-black select-none pointer-events-none">$</span>
                                        <input type="number" step="0.01" name="purchase_price" id="purchase_price" 
                                               value="{{ old('purchase_price', $product->purchase_price ?? '') }}" required
                                               class="input-field pl-8 pr-4 text-right" placeholder="0.00">
                                    </div>
                                    @error('purchase_price') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="selling_price" class="label-md block mb-2 uppercase tracking-wider">Precio Venta</label>
                                    <div class="relative flex items-center">
                                        <span class="absolute left-4 text-[#be004c] text-xs font-black select-none pointer-events-none">$</span>
                                        <input type="number" step="0.01" name="selling_price" id="selling_price" 
                                               value="{{ old('selling_price', $product->selling_price ?? '') }}" required
                                               class="input-field pl-8 pr-4 border-[#be004c]/20 text-right font-medium text-[#be004c]" placeholder="0.00">
                                    </div>
                                    @error('selling_price') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="w-1/2">
                                <label for="stock" class="label-md block mb-2 uppercase tracking-wider">Stock Disponible</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock ?? 0) }}" required
                                       class="input-field" placeholder="0">
                                @error('stock') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="category_id" class="label-md block mb-2 uppercase tracking-wider">Categoría</label>
                                <select name="category_id" id="category_id" class="input-field">
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                            </div>
                        </div>

                            @if(!isset($product))
                            <div class="pt-6 border-t border-[#303334]/5">
                                <div class="flex items-center space-x-3 mb-6">
                                    <input type="checkbox" name="has_variants" id="has_variants" value="1" 
                                           class="w-5 h-5 text-[#be004c] border-gray-300 rounded focus:ring-[#be004c]">
                                    <label for="has_variants" class="headline-md text-sm text-[#303334]">¿Este producto tiene variantes? (Color, Talla, etc.)</label>
                                </div>

                                <div id="variants-section" class="hidden space-y-6 bg-[#f3f3f4]/50 p-6 rounded-2xl border border-dashed border-[#303334]/20">
                                    <div id="attributes-container" class="space-y-4">
                                        <div class="attribute-row grid grid-cols-1 md:grid-cols-2 gap-4 items-end bg-white p-4 rounded-xl shadow-sm border border-[#303334]/5">
                                            <div>
                                                <label class="label-md block mb-2 uppercase tracking-wider text-[10px]">Atributo</label>
                                                <input type="text" name="attributes[0][name]" class="input-field bg-[#faf9f9]" placeholder="Ej: Color">
                                            </div>
                                            <div class="flex space-x-2">
                                                <div class="flex-1">
                                                    <label class="label-md block mb-2 uppercase tracking-wider text-[10px]">Valores (separados por coma)</label>
                                                    <input type="text" name="attributes[0][values]" class="input-field bg-[#faf9f9]" placeholder="Ej: Rojo, Azul, Negro">
                                                </div>
                                                <button type="button" class="remove-attr p-3 text-red-400 hover:text-red-600 opacity-0 pointer-events-none">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <button type="button" id="add-attribute" class="label-md uppercase tracking-widest text-[#be004c] flex items-center space-x-2 hover:opacity-80 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <span>Añadir Atributo</span>
                                    </button>

                                    <div class="p-4 bg-[#be004c]/5 rounded-xl border border-[#be004c]/10">
                                        <p class="body-md text-[#be004c] text-xs">
                                            <strong>Nota:</strong> Al guardar, el sistema generará automáticamente todas las combinaciones posibles con stock en 0. Podrás editar el stock de cada una en el siguiente paso.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="pt-6 border-t border-[#303334]/5 flex justify-end space-x-4">
                            <a href="{{ route('products.index') }}" class="btn-secondary px-6 py-3 rounded-xl body-md text-[#5d5f60] hover:bg-[#f3f3f4] transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary px-12">
                                {{ isset($product) ? 'Actualizar Producto' : 'Guardar Producto' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let attrIndex = 1;

    // Image preview functionality
    $('#image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').html(`<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`);
            }
            reader.readAsDataURL(file);
        }
    });

    $('#has_variants').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('#variants-section').toggleClass('hidden', !isChecked);
        
        if (isChecked) {
            $('#stock').val(0).prop('readonly', true).addClass('opacity-50');
            $('#variants-section input').prop('required', true);
        } else {
            $('#stock').prop('readonly', false).removeClass('opacity-50');
            $('#variants-section input').prop('required', false);
        }
    });

    $('#add-attribute').on('click', function() {
        const newRow = `
            <div class="attribute-row grid grid-cols-1 md:grid-cols-2 gap-4 items-end bg-white p-4 rounded-xl shadow-sm border border-[#303334]/5">
                <div>
                    <label class="label-md block mb-2 uppercase tracking-wider text-[10px]">Atributo</label>
                    <input type="text" name="attributes[${attrIndex}][name]" class="input-field bg-[#faf9f9]" placeholder="Ej: Talla" required>
                </div>
                <div class="flex space-x-2">
                    <div class="flex-1">
                        <label class="label-md block mb-2 uppercase tracking-wider text-[10px]">Valores (separados por coma)</label>
                        <input type="text" name="attributes[${attrIndex}][values]" class="input-field bg-[#faf9f9]" placeholder="Ej: S, M, L" required>
                    </div>
                    <button type="button" class="remove-attr p-3 text-red-400 hover:text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        `;
        $('#attributes-container').append(newRow);
        attrIndex++;
    });

    $(document).on('click', '.remove-attr', function() {
        $(this).closest('.attribute-row').remove();
    });
});
</script>
@endpush
