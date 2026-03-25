@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
<main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-6 sm:px-12">
            <div class="max-w-4xl mx-auto">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end mb-12 space-y-6 sm:space-y-0 text-center sm:text-left">
                    <div>
                        <h1 class="display-lg text-3xl sm:text-5xl text-[#303334] mb-2 tracking-tight font-black">Métodos de Pago</h1>
                        <p class="body-md text-[#5d5f60]">Gestiona las formas en que recibes pagos.</p>
                    </div>
                </div>

                <a href="{{ route('payment-methods.create') }}" class="fab group" title="Nuevo Método">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="absolute right-full mr-4 bg-[#303334] text-white px-3 py-1.5 rounded-xl text-xs opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none shadow-lg">
                        Nuevo Método
                    </span>
                </a>

                @if(session('success'))
                    <div class="mb-8 p-4 bg-[#ffd9e2] text-[#be004c] rounded-xl body-md font-medium animate-fade-in text-sm text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    @forelse($methods as $method)
                        <div class="card flex flex-col sm:flex-row items-center justify-between group hover:bg-[#f3f3f4] transition-colors p-6 sm:p-4 space-y-4 sm:space-y-0">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 shrink-0 rounded-xl bg-[#ffd9e2]/30 flex items-center justify-center text-[#be004c]">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <span class="headline-md text-base sm:text-lg text-[#303334] font-bold">{{ $method->name }}</span>
                            </div>
                            
                            <div class="flex items-center space-x-4 sm:space-x-2 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('payment-methods.edit', $method) }}" class="p-2 text-[#5d5f60] hover:text-[#be004c] hover:bg-white rounded-lg transition-colors text-sm">
                                    Editar
                                </a>
                                <form action="{{ route('payment-methods.destroy', $method) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-[#5d5f60] hover:text-[#f97386] hover:bg-white rounded-lg transition-colors text-sm">
                                        Borrar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 card h-48 flex items-center justify-center border-dashed border-2 border-[#303334]/10 bg-transparent">
                            <p class="body-md text-[#5d5f60] italic">No hay métodos de pago registrados.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
