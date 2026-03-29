@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-5xl mx-auto">
                <div class="mb-12">
                    <a href="{{ route('orders.index') }}" class="label-md uppercase tracking-widest hover:text-[#be004c] transition-colors mb-4 inline-block">
                        ← Volver a pedidos
                    </a>
                    <h1 class="display-lg text-[#303334] mb-2">Crear Nuevo Pedido</h1>
                    <p class="body-md text-[#5d5f60]">Selecciona los productos y cantidades para generar la orden.</p>
                </div>

                @if(session('error'))
                    <div class="mb-8 p-4 bg-[#f97386]/10 text-[#f97386] rounded-xl body-md font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('orders.store') }}" method="POST" id="order-form">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Left: Info & Items -->
                        <div class="lg:col-span-2 space-y-8">
                            <div class="card">
                                <h3 class="label-md uppercase tracking-widest mb-6">Información del Cliente</h3>
                                <div>
                                    <label for="customer_name" class="label-md block mb-2 uppercase tracking-wider">Nombre Completo</label>
                                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required
                                           class="input-field" placeholder="Ej: Alejandra García">
                                    @error('customer_name') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="card">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="label-md uppercase tracking-widest">Productos del Pedido</h3>
                                    <button type="button" id="add-item" class="text-[#be004c] label-md font-bold hover:underline">
                                        + Agregar Producto
                                    </button>
                                </div>

                                <div id="items-container" class="space-y-4">
                                    <!-- Dynamic items will be injected here -->
                                </div>

                                @error('items') <p class="mt-4 text-sm text-[#f97386]">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Right: Summary -->
                        <div class="space-y-8">
                            <div class="card sticky top-12">
                                <h3 class="label-md uppercase tracking-widest mb-6 border-b border-[#303334]/5 pb-4">Resumen de Venta</h3>
                                
                                <div class="space-y-4 mb-8" id="summary-items">
                                    <p class="body-sm text-[#5d5f60] italic">No hay productos seleccionados.</p>
                                </div>

                                <div class="pt-6 border-t border-[#303334]/5 space-y-2">
                                    @if($latestRate)
                                        <div class="flex justify-between items-center bg-[#f3f3f4]/50 p-3 rounded-xl mb-4">
                                            <div>
                                                <p class="text-[10px] label-md uppercase tracking-wider mb-0.5">Tasa Aplicada ({{ $latestRate->currency }})</p>
                                                <p class="body-sm font-bold text-[#303334]">${{ number_format($latestRate->value, 2) }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] text-[#5d5f60] uppercase">Actualizado</p>
                                                <p class="text-[10px] font-medium text-[#5d5f60]">{{ $latestRate->last_update->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex justify-between items-end">
                                        <div class="flex flex-col">
                                            <span class="label-md uppercase tracking-widest">Total a Pagar</span>
                                            <span class="body-md font-bold text-[#5d5f60] mt-1" id="total-bs-display">BsS 0.00</span>
                                        </div>
                                        <span class="headline-md text-3xl text-[#be004c]" id="total-display">$0.00</span>
                                    </div>
                                </div>

                                <button type="submit" class="w-full btn-primary bg-[#303334] mt-8 py-4 rounded-xl text-lg hover:bg-[#be004c] transition-all duration-300 shadow-lg shadow-[#303334]/10">
                                    Generar Pedido
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<!-- Template for Order Item -->
<template id="item-template">
    <div class="order-item flex flex-col p-6 rounded-2xl bg-white border border-[#303334]/5 hover:border-[#be004c]/20 transition-all mb-4">
        <div class="flex items-end space-x-4">
            <div class="flex-1">
                <label class="label-md block mb-2 opacity-60">Producto</label>
                <select name="items[INDEX][product_id]" class="select2 input-field product-select" required data-placeholder="Seleccionar producto...">
                    <option></option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" 
                                data-price="{{ $product->selling_price }}" 
                                data-stock="{{ $product->stock }}"
                                data-has-variants="{{ $product->has_variants ? '1' : '0' }}">
                            {{ $product->name }} (${{ number_format($product->selling_price, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-24">
                <label class="label-md block mb-2 opacity-60">Cant.</label>
                <input type="number" name="items[INDEX][quantity]" class="input-field quantity-input text-center" value="1" min="1" required>
            </div>
            <div class="w-32 text-right">
                <span class="label-md block mb-2 opacity-60">Subtotal</span>
                <div class="flex flex-col">
                    <span class="body-sm font-medium text-[#5d5f60] item-subtotal-bs">BsS 0.00</span>
                    <span class="headline-md item-subtotal text-[#303334]">$0.00</span>
                </div>
            </div>
            <button type="button" class="remove-item p-3 text-[#f97386] hover:bg-[#f3f3f4] rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>

        <!-- Variant Selection Row (Hidden by default) -->
        <div class="variant-row mt-4 pt-4 border-t border-[#303334]/5 hidden">
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <label class="label-md block mb-2 opacity-60">Opción / Variante</label>
                    <select name="items[INDEX][product_variant_id]" class="select2 input-field variant-select" data-placeholder="Cargando opciones...">
                        <option></option>
                    </select>
                </div>
                <div class="w-56 pt-6">
                    <div class="bg-[#f3f3f4] px-4 py-2 rounded-lg flex justify-between items-center">
                        <span class="label-md text-[10px]">Stock Disponible</span>
                        <span class="headline-md text-sm text-[#be004c] variant-stock-display">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
$(document).ready(function() {
    const container = $('#items-container');
    const template = $('#item-template').html();
    const totalDisplay = $('#total-display');
    const totalBsDisplay = $('#total-bs-display');
    const summaryList = $('#summary-items');
    const exchangeRate = {{ $latestRate->value ?? 0 }};
    let itemIndex = 0;

    function addItem() {
        const index = itemIndex++;
        const content = template.replaceAll('INDEX', index);
        const row = $(content).appendTo(container);

        const productSelect = row.find('.product-select');
        const variantSelect = row.find('.variant-select');
        const variantRow = row.find('.variant-row');
        const qtyInput = row.find('.quantity-input');
        const removeBtn = row.find('.remove-item');

        // Initialize Product Select2
        productSelect.select2({
            width: '100%',
            placeholder: 'Seleccionar producto...',
            allowClear: true
        });

        // Initialize Variant Select2
        variantSelect.select2({
            width: '100%',
            placeholder: 'Seleccione primero un producto',
        });

        const updateRow = () => {
            const productOption = productSelect.find(':selected')[0];
            
            if (!productOption || !productOption.value) {
                variantRow.addClass('hidden');
                row.find('.item-subtotal').text('$0.00');
                row.find('.item-subtotal-bs').text('BsS 0.00');
                calculateTotal();
                return;
            }

            const hasVariants = productOption.dataset.hasVariants === '1';
            
            if (hasVariants) {
                const variantOption = variantSelect.find(':selected')[0];
                if (!variantOption || !variantOption.value) {
                    row.find('.item-subtotal').text('$0.00');
                    calculateTotal();
                    return;
                }

                const price = parseFloat(variantOption.dataset.price);
                const stock = parseInt(variantOption.dataset.stock);
                let quantity = parseInt(qtyInput.val() || 0);

                if (quantity > stock) {
                    alert(`Stock insuficiente. Solo quedan ${stock} unidades.`);
                    qtyInput.val(stock);
                    quantity = stock;
                }

                const subtotal = price * quantity;
                row.find('.item-subtotal').text(`$${subtotal.toFixed(2)}`);
                row.find('.item-subtotal-bs').text(`BsS ${(subtotal * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2})}`);
            } else {
                variantRow.addClass('hidden');
                const price = parseFloat(productOption.dataset.price);
                const stock = parseInt(productOption.dataset.stock);
                let quantity = parseInt(qtyInput.val() || 0);

                if (quantity > stock) {
                    alert(`Stock insuficiente. Solo quedan ${stock} unidades.`);
                    qtyInput.val(stock);
                    quantity = stock;
                }

                const subtotal = price * quantity;
                row.find('.item-subtotal').text(`$${subtotal.toFixed(2)}`);
                row.find('.item-subtotal-bs').text(`BsS ${(subtotal * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2})}`);
            }
            
            calculateTotal();
        };

        productSelect.on('change', function() {
            const productOption = this.options[this.selectedIndex];
            if (productOption && productOption.dataset.hasVariants === '1') {
                variantRow.removeClass('hidden');
                variantSelect.empty().append('<option></option>').trigger('change');
                variantSelect.prop('disabled', true).val(null).trigger('change');
                
                // Fetch variants via AJAX
                $.ajax({
                    url: `/products/${this.value}/variants/api`,
                    method: 'GET',
                    success: function(data) {
                        variantSelect.prop('disabled', false);
                        data.forEach(v => {
                            const newOption = new Option(v.label, v.id, false, false);
                            newOption.dataset.price = v.price;
                            newOption.dataset.stock = v.stock;
                            variantSelect.append(newOption);
                        });
                        variantSelect.trigger('change');
                    }
                });
            } else {
                variantRow.addClass('hidden');
                updateRow();
            }
        });

        variantSelect.on('change', function() {
            const option = this.options[this.selectedIndex];
            if (option && option.value) {
                row.find('.variant-stock-display').text(option.dataset.stock);
            } else {
                row.find('.variant-stock-display').text('-');
            }
            updateRow();
        });

        qtyInput.on('input', updateRow);
        
        removeBtn.on('click', function(e) {
            e.preventDefault();
            productSelect.select2('destroy');
            variantSelect.select2('destroy');
            row.remove();
            calculateTotal();
        });
    }

    function calculateTotal() {
        let total = 0;
        summaryList.empty();
        
        container.find('.order-item').each(function() {
            const row = $(this);
            const productSelect = row.find('.product-select');
            const variantSelect = row.find('.variant-select');
            const qty = parseInt(row.find('.quantity-input').val()) || 0;
            
            const productOption = productSelect.find(':selected')[0];
            const hasVariants = productOption && productOption.dataset.hasVariants === '1';
            
            if (productOption && productOption.value) {
                let price = 0;
                let itemName = productOption.text.split(' ($')[0];

                if (hasVariants) {
                    const variantOption = variantSelect.find(':selected')[0];
                    if (variantOption && variantOption.value) {
                        price = parseFloat(variantOption.dataset.price);
                        itemName += ` (${variantOption.text})`;
                    } else {
                        return; // Wait for variant selection
                    }
                } else {
                    price = parseFloat(productOption.dataset.price);
                }

                const subtotal = price * qty;
                const subtotalBs = subtotal * exchangeRate;
                total += subtotal;

                const p = $('<div class="flex justify-between items-center bg-[#f3f3f4]/30 p-2 rounded-lg mb-2"></div>')
                    .append(`<span class="body-sm text-[#5d5f60] truncate mr-2">${itemName} x${qty}</span>`)
                    .append(`<div class="text-right flex flex-col items-end">
                                <span class="body-xs text-[#5d5f60] leading-none mb-1">BsS ${subtotalBs.toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
                                <span class="body-sm font-medium text-[#303334] whitespace-nowrap leading-none">$${subtotal.toFixed(2)}</span>
                             </div>`);
                
                summaryList.append(p);
            }
        });

        if (summaryList.is(':empty')) {
            summaryList.html('<p class="body-sm text-[#5d5f60] italic">No hay productos seleccionados.</p>');
        }

        totalDisplay.text(`$${total.toFixed(2)}`);
        totalBsDisplay.text(`BsS ${(total * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2})}`);
    }

    $('#add-item').on('click', function(e) {
        e.preventDefault();
        addItem();
    });
    
    if (container.children().length === 0) {
        addItem();
    }
});
</script>
@endpush
@endsection
