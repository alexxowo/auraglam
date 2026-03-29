@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-3xl mx-auto">
                <div class="mb-12">
                    <a href="{{ route('payments.index') }}" class="label-md uppercase tracking-widest hover:text-[#be004c] transition-colors mb-4 inline-block">
                        ← Volver a pagos
                    </a>
                    <h1 class="display-lg text-[#303334] mb-2">Registrar Pago</h1>
                    <p class="body-md text-[#5d5f60]">Asocia un nuevo pago a un pedido existente.</p>
                </div>

                <div class="card">
                    <form action="{{ route('payments.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="space-y-6">
                            <div>
                                <label for="order_id" class="label-md block mb-2 uppercase tracking-wider">Seleccionar Pedido</label>
                                <select name="order_id" id="order_id" required class="select2 input-field" data-placeholder="Selecciona un pedido...">
                                    <option></option>
                                    @foreach($orders as $order)
                                        @if($order->pending_amount > 0 || $selectedOrderId == $order->id)
                                            <option value="{{ $order->id }}" 
                                                    data-pending="{{ $order->pending_amount }}"
                                                    {{ (old('order_id') == $order->id || $selectedOrderId == $order->id) ? 'selected' : '' }}>
                                                #{{ $order->document_number ?? $order->id }} - {{ $order->customer_name }} (Pendiente: ${{ number_format($order->pending_amount, 2) }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('order_id') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="payment_method_id" class="label-md block mb-2 uppercase tracking-wider">Método de Pago</label>
                                    <select name="payment_method_id" id="payment_method_id" required class="select2 input-field" data-placeholder="Selecciona un método...">
                                        <option></option>
                                        @foreach($methods as $method)
                                            <option value="{{ $method->id }}" {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                                {{ $method->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('payment_method_id') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="amount" class="label-md block mb-2 uppercase tracking-wider">Monto a Pagar</label>
                                    <div class="relative flex items-center">
                                        <span class="absolute left-4 text-[#be004c] text-xs font-black select-none pointer-events-none">$</span>
                                        <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" required
                                               class="input-field pl-8 pr-4 text-right font-medium" placeholder="0.00">
                                    </div>
                                    @error('amount') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="payment_date" class="label-md block mb-2 uppercase tracking-wider">Fecha del Pago</label>
                                    <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required
                                           class="input-field">
                                    @error('payment_date') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="reference" class="label-md block mb-2 uppercase tracking-wider">Referencia (Opcional)</label>
                                    <input type="text" name="reference" id="reference" value="{{ old('reference') }}"
                                           class="input-field" placeholder="Comp. #12345">
                                    @error('reference') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-[#303334]/5 flex justify-end space-x-4">
                            <a href="{{ route('payments.index') }}" class="btn-secondary px-6 py-3 rounded-xl body-md text-[#5d5f60] hover:bg-[#f3f3f4] transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary px-12">
                                Registrar Pago
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
    $('.select2').each(function() {
        $(this).select2({
            width: '100%',
            dropdownParent: $(this).parent(),
            placeholder: $(this).data('placeholder'),
            allowClear: true
        });
    });

    const amountInput = $('#amount');
    const orderSelect = $('#order_id');

    function updateAmount() {
        const selected = orderSelect.find(':selected');
        if (selected.val() && !amountInput.val()) {
            const pending = selected.data('pending');
            amountInput.val(parseFloat(pending).toFixed(2));
        }
    }

    orderSelect.on('change', updateAmount);

    // Initial check if an order is pre-selected
    if (orderSelect.val()) {
        updateAmount();
    }
});
</script>
@endpush
@endsection
