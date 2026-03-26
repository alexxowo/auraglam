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
    <div class="order-item flex items-end space-x-4 p-4 rounded-xl bg-[#f3f3f4]/50 border border-transparent hover:border-[#be004c]/10 transition-all">
        <div class="flex-1">
            <label class="label-md block mb-2 opacity-60">Producto</label>
            <select name="items[INDEX][product_id]" class="select2 input-field product-select" required data-placeholder="Seleccionar producto...">
                <option></option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->stock }}">
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
        <button type="button" class="remove-item p-3 text-[#f97386] hover:bg-white rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
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

        const select = row.find('.product-select');
        const qty = row.find('.quantity-input');
        const remove = row.find('.remove-item');

        // Initialize Select2
        select.select2({
            width: '100%',
            placeholder: 'Seleccionar producto...',
            allowClear: true
        });

        const updateRow = () => {
            const option = select.find(':selected')[0];
            
            if (!option || !option.value) {
                row.find('.item-subtotal').text('$0.00');
                row.find('.item-subtotal-bs').text('BsS 0.00');
                calculateTotal();
                return;
            }

            const price = parseFloat(option.dataset.price || 0);
            const stock = parseInt(option.dataset.stock || 0);
            let quantity = parseInt(qty.val() || 0);

            if (quantity > stock) {
                alert(`Solo quedan ${stock} unidades de este producto.`);
                qty.val(stock);
                quantity = stock;
            }

            const subtotal = price * quantity;
            row.find('.item-subtotal').text(`$${subtotal.toFixed(2)}`);
            row.find('.item-subtotal-bs').text(`BsS ${(subtotal * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
            calculateTotal();
        };

        select.on('change', updateRow);
        qty.on('input', updateRow);
        
        remove.on('click', function(e) {
            e.preventDefault();
            select.select2('destroy');
            row.remove();
            calculateTotal();
        });

        updateRow();
    }

    function calculateTotal() {
        let total = 0;
        summaryList.empty();
        
        container.find('.order-item').each(function() {
            const row = $(this);
            const select = row.find('.product-select');
            const qty = parseInt(row.find('.quantity-input').val()) || 0;
            const option = select.find(':selected')[0];
            
            if (option && option.value) {
                const price = parseFloat(option.dataset.price);
                const subtotal = price * qty;
                const subtotalBs = subtotal * exchangeRate;
                total += subtotal;

                const p = $('<div class="flex justify-between items-center bg-[#f3f3f4]/30 p-2 rounded-lg mb-2"></div>')
                    .append(`<span class="body-sm text-[#5d5f60] truncate mr-2">${option.text.split(' ($')[0]} x${qty}</span>`)
                    .append(`<div class="text-right flex flex-col items-end">
                                <span class="body-xs text-[#5d5f60] leading-none mb-1">BsS ${subtotalBs.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                                <span class="body-sm font-medium text-[#303334] whitespace-nowrap leading-none">$${subtotal.toFixed(2)}</span>
                             </div>`);
                
                summaryList.append(p);
            }
        });

        if (summaryList.is(':empty')) {
            summaryList.html('<p class="body-sm text-[#5d5f60] italic">No hay productos seleccionados.</p>');
        }

        totalDisplay.text(`$${total.toFixed(2)}`);
        totalBsDisplay.text(`BsS ${(total * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`);
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
