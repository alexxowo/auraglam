@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-[#faf9f9]">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <h2 class="display-text headline-md text-[#be004c] mb-2">Aura Glam</h2>
        <p class="body-md text-[#5d5f60]">The Editorial Experience in Sales</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="card bg-white/80 backdrop-blur-xl">
            <h3 class="headline-md mb-6 font-bold text-[#303334]">Bienvenido</h3>
            
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="label-md block mb-2 uppercase tracking-wider">Correo Electrónico</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="input-field @error('email') border-red-500 @enderror" 
                           placeholder="admin@auraglam.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="label-md block mb-2 uppercase tracking-wider">Contraseña</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="input-field" placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-[#be004c] border-gray-300 rounded focus:ring-[#fc306f]">
                        <label for="remember" class="ml-2 block body-md text-[#5d5f60]">Recordarme</label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-primary w-full text-center">
                        Entrar al Dashboard
                    </button>
                </div>
            </form>
            
            <div class="mt-6 pt-6 border-t border-gray-100/50">
                <p class="body-md text-center text-[#5d5f60]">
                    ¿No tienes acceso? <span class="text-[#be004c] font-medium">Contacta al administrador</span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
