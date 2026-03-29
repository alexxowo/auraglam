@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-3xl mx-auto">
                <div class="mb-12">
                    <a href="{{ route('categories.index') }}" class="label-md uppercase tracking-widest hover:text-[#be004c] transition-colors mb-4 inline-block">
                        ← Volver al listado
                    </a>
                    <h1 class="display-lg text-[#303334] mb-2">{{ isset($category) ? 'Editar Categoría' : 'Nueva Categoría' }}</h1>
                    <p class="body-md text-[#5d5f60]">Define los detalles de la categoría para organizar tus productos.</p>
                </div>

                <div class="card">
                    <form action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}" method="POST" class="space-y-8">
                        @csrf
                        @if(isset($category))
                            @method('PUT')
                        @endif

                        <div class="space-y-6">
                            <div>
                                <label for="name" class="label-md block mb-2 uppercase tracking-wider">Nombre de la Categoría</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}" required
                                       class="input-field" placeholder="Ej: Vestidos, Accesorios, Tops...">
                                @error('name') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="description" class="label-md block mb-2 uppercase tracking-wider">Descripción (Opcional)</label>
                                <textarea name="description" id="description" rows="4" 
                                          class="input-field shadow-sm" placeholder="Breve descripción de los productos que pertenecen a esta categoría...">{{ old('description', $category->description ?? '') }}</textarea>
                                @error('description') <p class="mt-2 text-xs text-[#f97386]">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-center space-x-3 p-4 bg-[#f3f3f4] rounded-2xl w-fit">
                                <span class="label-md uppercase tracking-wider mr-2">Estado:</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" class="sr-only peer" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }} value="1">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:inset-s-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#be004c]"></div>
                                    <span class="ms-3 body-md font-medium text-[#303334]">Categoría Activa</span>
                                </label>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-[#303334]/5 flex justify-end space-x-4">
                            <a href="{{ route('categories.index') }}" class="px-6 py-3 rounded-xl body-md text-[#5d5f60] hover:bg-[#f3f3f4] transition-colors font-semibold">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-primary px-12">
                                {{ isset($category) ? 'Actualizar Categoría' : 'Guardar Categoría' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
