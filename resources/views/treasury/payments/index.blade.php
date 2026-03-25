@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-between items-end mb-12">
                    <div>
                        <h1 class="display-lg text-[#303334] mb-2 font-black tracking-tight">Pagos Recibidos</h1>
                        <p class="body-md text-[#5d5f60]">Historial de transacciones de clientes.</p>
                    </div>
                </div>

                <a href="{{ route('payments.create') }}" class="fab group" title="Registrar Pago">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="absolute right-full mr-4 bg-[#303334] text-white px-3 py-1.5 rounded-xl text-xs opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none shadow-lg">
                        Registrar Pago
                    </span>
                </a>

                @if(session('success'))
                    <div class="mb-8 p-4 bg-[#ffd9e2] text-[#be004c] rounded-xl body-md font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card overflow-hidden">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="min-w-full divide-y divide-[#303334]/5">
                            <thead class="bg-[#f3f3f4]/50">
                                <tr>
                                    <th class="px-6 py-4 text-left label-md uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-4 text-left label-md uppercase tracking-wider">Nº Pedido</th>
                                    <th class="px-6 py-4 text-left label-md uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-4 text-left label-md uppercase tracking-wider">Método</th>
                                    <th class="px-6 py-4 text-left label-md uppercase tracking-wider">Referencia</th>
                                    <th class="px-6 py-4 text-right label-md uppercase tracking-wider">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#303334]/5">
                                @forelse($payments as $payment)
                                    <tr class="hover:bg-[#f3f3f4]/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap body-md text-[#5d5f60]">
                                            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap body-md text-[#5d5f60]">
                                            <button onclick="openOrderModal('{{ $payment->order->document_number }}')" 
                                            class="hover:text-[#be004c] font-medium transition-colors border-b border-dashed border-[#be004c]/20">
                                                {{ $payment->order->document_number ?? '#'.$payment->order->id }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap headline-md text-sm text-[#303334]">
                                            {{ $payment->order->customer_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 bg-[#ffd9e2]/50 text-[#be004c] text-xs font-bold rounded-full uppercase tracking-tighter">
                                                {{ $payment->paymentMethod->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap body-md text-[#5d5f60]">
                                            {{ $payment->reference ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right headline-md text-[#303334]">
                                            ${{ number_format($payment->amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center body-md text-[#5d5f60] italic">
                                            No se han registrado pagos aún.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal: Order Info (AJAX) -->
    <div id="order-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6 bg-[#303334]/40 backdrop-blur-md" onclick="if(event.target === this) closeOrderModal()">
        <div class="card w-full max-w-2xl max-h-[90vh] flex flex-col bg-white/95 backdrop-blur-2xl border border-white/50 shadow-2xl relative overflow-hidden animate-fade-in mx-auto">
            <!-- Glass Header Decor -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#be004c]/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <div class="flex justify-between items-start mb-6 relative z-10 p-2 sm:p-0">
                <div>
                    <h2 id="modal-doc-number" class="display-sm text-xl sm:text-2xl text-[#303334] mb-1 font-bold">Cargando...</h2>
                    <p class="body-md text-[#5d5f60] text-sm sm:text-base">Cliente: <span id="modal-customer" class="font-bold">-</span></p>
                </div>
                <button type="button" onclick="closeOrderModal()" class="p-2 hover:bg-[#f3f3f4] rounded-full transition-colors text-[#5d5f60] touch-manipulation">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div id="modal-content" class="flex-1 flex flex-col space-y-6 relative z-10 opacity-0 transition-opacity duration-300 overflow-hidden">
                <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar min-h-0">
                    <table class="w-full text-left">
                        <thead class="border-b border-[#303334]/5 sticky top-0 bg-white/80 backdrop-blur-sm">
                            <tr>
                                <th class="pb-3 label-md text-[10px] uppercase">Item</th>
                                <th class="pb-3 text-center label-md text-[10px] uppercase">Cant.</th>
                                <th class="pb-3 text-right label-md text-[10px] uppercase">Precio</th>
                            </tr>
                        </thead>
                        <tbody id="modal-items-body" class="divide-y divide-[#303334]/5">
                            <!-- Items injected here -->
                        </tbody>
                    </table>
                </div>

                <div class="pt-6 border-t border-[#303334]/5 flex justify-between items-center text-[#be004c] shrink-0">
                    <span class="label-md uppercase tracking-[0.2em] font-bold text-xs sm:text-sm">Total Venta</span>
                    <span id="modal-total-amount" class="headline-md text-xl sm:text-2xl font-bold">$0.00</span>
                </div>

                <div class="bg-[#f3f3f4]/50 rounded-xl p-4 shrink-0">
                    <div class="flex justify-between items-center mb-3">
                        <span class="label-md uppercase tracking-wider opacity-60 text-[10px] sm:text-xs">Historial de Pagos</span>
                        <span id="modal-total-paid" class="label-md font-bold text-[#be004c] text-[10px] sm:text-xs">
                            Pagado: $0.00
                        </span>
                    </div>
                    <div id="modal-payments-list" class="space-y-2 text-[10px] sm:text-[11px] body-sm">
                        <!-- Payments injected here -->
                    </div>
                </div>

                <div class="mt-4 flex justify-end shrink-0">
                    <a id="modal-show-url" href="#" class="btn-primary w-full sm:w-auto text-center py-3 text-sm">
                        Ver Detalles Completos
                    </a>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="modal-loader" class="absolute inset-0 flex items-center justify-center bg-white/60 z-20 transition-opacity duration-300">
                <div class="w-8 h-8 border-4 border-[#be004c] border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>
    </div>
</div>

<script>
function getModalElements() {
    return {
        modal: document.getElementById('order-modal'),
        loader: document.getElementById('modal-loader'),
        content: document.getElementById('modal-content'),
        docNum: document.getElementById('modal-doc-number'),
        customer: document.getElementById('modal-customer'),
        total: document.getElementById('modal-total-amount'),
        paid: document.getElementById('modal-total-paid'),
        showUrl: document.getElementById('modal-show-url'),
        itemsBody: document.getElementById('modal-items-body'),
        paymentsList: document.getElementById('modal-payments-list')
    };
}

async function openOrderModal(docNum) {
    const el = getModalElements();
    if (!el.modal) return;

    el.modal.classList.remove('hidden');
    el.modal.classList.add('flex');
    el.loader.style.opacity = '1';
    el.content.style.opacity = '0';
    el.loader.classList.remove('hidden');
    
    // Update URL
    const url = new URL(window.location);
    url.searchParams.set('order_info', docNum);
    window.history.pushState({}, '', url);

    try {
        const response = await fetch(`/orders/details/${docNum}`);
        if (!response.ok) throw new Error('Error al cargar datos');
        const data = await response.json();

        // Populate
        el.docNum.textContent = data.document_number;
        el.customer.textContent = data.customer_name;
        el.total.textContent = `$${data.total_amount.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        el.paid.textContent = `Pagado: $${data.total_paid.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        el.showUrl.href = data.show_url;

        // Items
        el.itemsBody.innerHTML = '';
        data.items.forEach(item => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="py-3 body-md text-sm text-[#303334]">${item.product_name}</td>
                <td class="py-3 text-center body-md text-sm text-[#5d5f60]">${item.quantity}</td>
                <td class="py-3 text-right body-md text-sm font-medium text-[#303334]">$${parseFloat(item.subtotal).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
            `;
            el.itemsBody.appendChild(tr);
        });

        // Payments
        el.paymentsList.innerHTML = '';
        data.payments.forEach(p => {
            const dateParsed = new Date(p.date).toLocaleDateString();
            const div = document.createElement('div');
            div.className = 'flex justify-between';
            div.innerHTML = `
                <span class="text-[#5d5f60]">${dateParsed} · ${p.method}</span>
                <span class="font-medium text-[#303334]">$${parseFloat(p.amount).toLocaleString(undefined, {minimumFractionDigits: 2})}</span>
            `;
            el.paymentsList.appendChild(div);
        });

        el.loader.style.opacity = '0';
        el.content.style.opacity = '1';
        setTimeout(() => el.loader.classList.add('hidden'), 300);
    } catch (error) {
        console.error(error);
        alert('No se pudo cargar la información del pedido.');
        closeOrderModal();
    }
}

function closeOrderModal() {
    const el = getModalElements();
    if (!el.modal) return;

    el.modal.classList.add('hidden');
    el.modal.classList.remove('flex');
    
    // Update URL
    const url = new URL(window.location);
    url.searchParams.delete('order_info');
    window.history.pushState({}, '', url);
}

// Handle browser back/forward
window.onpopstate = function() {
    const params = new URLSearchParams(window.location.search);
    const docNum = params.get('order_info');
    if (docNum) {
        openOrderModal(docNum);
    } else {
        closeOrderModal();
    }
};

// Check on load
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const docNum = params.get('order_info');
    if (docNum) {
        openOrderModal(docNum);
    }
});
</script>
@endsection
