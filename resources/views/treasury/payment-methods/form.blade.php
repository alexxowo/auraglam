@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-2xl mx-auto">
                <div class="mb-12">
                    <a href="{{ route('payment-methods.index') }}" class="label-md uppercase tracking-widest hover:text-[#be004c] transition-colors mb-4 inline-block">
                        ← Volver a métodos
                    </a>
                    <h1 class="display-lg text-[#303334] mb-2">{{ isset($paymentMethod) ? 'Editar Método' : 'Nuevo Método' }}</h1>
                    <p class="body-md text-[#5d5f60]">Define un nombre descriptivo para el método de pago.</p>
                </div>

                <div class="card">
                    <form action="{{ isset($paymentMethod) ? route('payment-methods.update', $paymentMethod) : route('payment-methods.store') }}" method="POST" class="space-y-8">
                        @csrf
                        @if(isset($paymentMethod))
                            @method('PUT')
                        @endif

                        <div>
                            <label for="name" class="label-md block mb-2 uppercase tracking-wider">Nombre del Método</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $paymentMethod->name ?? '') }}" required
                                   class="input-field" placeholder="Ej: Efectivo, Transferencia, Tarjeta">
                            @error('name') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-6 border-t border-[#303334]/5 flex justify-end space-x-4">
                            <a href="{{ route('payment-methods.index') }}" class="btn-secondary px-6 py-3 rounded-xl body-md text-[#5d5f60] hover:bg-[#f3f3f4] transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary px-12">
                                {{ isset($paymentMethod) ? 'Actualizar Método' : 'Guardar Método' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
