<!-- Mobile Header -->
<div class="md:hidden flex items-center justify-between px-6 h-16 bg-white/90 backdrop-blur-xl border-b border-[#303334]/10 sticky top-0 z-40 shrink-0 w-full shadow-sm">
    <span class="display-text headline-md text-[#be004c] text-xl tracking-tight">Aura Glam</span>
    <button onclick="toggleMobileMenu()" class="p-2.5 text-[#be004c] bg-[#ffd9e2]/40 hover:bg-[#ffd9e2]/60 active:scale-95 rounded-xl transition-all duration-200 shadow-sm border border-[#be004c]/5">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>
</div>

<!-- Sidebar / Nav (Desktop) -->
<div class="hidden md:flex md:shrink-0">
    <div class="flex flex-col w-64 bg-white/40 backdrop-blur-md border-r border-[#303334]/5">
        <div class="flex items-center h-16 shrink-0 px-6">
            <span class="display-text headline-md text-[#be004c]">Aura Glam</span>
        </div>
        <div class="flex-1 flex flex-col overflow-y-auto pt-5 pb-4 custom-scrollbar">
            <nav class="mt-5 flex-1 px-4 space-y-2">
                @php
                    $navItems = [
                        ['route' => 'dashboard', 'label' => 'Dashboard'],
                        ['route' => 'products.index', 'label' => 'Productos', 'pattern' => 'products.*'],
                        ['label' => 'Tesorería', 'is_header' => true],
                        ['route' => 'payments.index', 'label' => 'Pagos', 'pattern' => 'payments.*'],
                        ['route' => 'payment-methods.index', 'label' => 'Métodos de Pago', 'pattern' => 'payment-methods.*'],
                        ['label' => 'Ventas', 'is_header' => true],
                        ['route' => 'orders.index', 'label' => 'Pedidos', 'pattern' => 'orders.*'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @if($item['is_header'] ?? false)
                        <div class="pt-4 pb-2">
                            <span class="px-4 label-md uppercase tracking-[0.2em] opacity-40">{{ $item['label'] }}</span>
                        </div>
                    @else
                        <a href="{{ route($item['route']) }}" 
                           class="{{ request()->routeIs($item['pattern'] ?? $item['route']) ? 'bg-[#ffd9e2] text-[#be004c]' : 'text-[#5d5f60] hover:bg-[#f3f3f4]' }} group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors">
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>
        <div class="shrink-0 flex border-t border-[#303334]/5 p-4">
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-sm font-medium text-[#5d5f60] hover:bg-[#f3f3f4] rounded-xl transition-colors">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Mobile Menu Drawer (Overlay) -->
<div id="mobile-menu" class="fixed inset-0 z-50 hidden items-end sm:items-start justify-start p-0">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-[#303334]/20 backdrop-blur-sm transition-opacity" onclick="toggleMobileMenu()"></div>
    
    <!-- Drawer -->
    <div class="relative w-72 max-w-[80vw] h-full bg-white shadow-2xl flex flex-col animate-slide-in-left">
        <div class="flex items-center justify-between h-16 px-6 border-b border-[#303334]/5">
            <span class="display-text headline-md text-[#be004c] text-lg">Menú</span>
            <button onclick="toggleMobileMenu()" class="p-2 text-[#5d5f60]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2 custom-scrollbar">
            @foreach($navItems as $item)
                @if($item['is_header'] ?? false)
                    <div class="pt-4 pb-2">
                        <span class="px-4 label-md uppercase tracking-[0.2em] opacity-40 text-[10px]">{{ $item['label'] }}</span>
                    </div>
                @else
                    <a href="{{ route($item['route']) }}" 
                       class="{{ request()->routeIs($item['pattern'] ?? $item['route']) ? 'bg-[#ffd9e2] text-[#be004c]' : 'text-[#5d5f60]' }} group flex items-center px-4 py-4 text-base font-medium rounded-xl transition-colors active:bg-[#f3f3f4]">
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </div>

        <div class="shrink-0 border-t border-[#303334]/5 p-4 bg-[#f3f3f4]/10">
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full text-center py-4 text-sm font-bold text-[#be004c] bg-[#ffd9e2]/30 rounded-xl transition-colors active:bg-[#ffd9e2]/50">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        menu.classList.add('flex');
        document.body.style.overflow = 'hidden';
    } else {
        menu.classList.add('hidden');
        menu.classList.remove('flex');
        document.body.style.overflow = '';
    }
}
</script>

<style>
@keyframes slide-in-left {
    from { transform: translateX(-100%); }
    to { transform: translateX(0); }
}
.animate-slide-in-left {
    animation: slide-in-left 0.3s ease-out forwards;
}
</style>
