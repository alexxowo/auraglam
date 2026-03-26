@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <!-- Main Content -->
    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-4xl mx-auto">
                <header class="mb-12 flex justify-between items-center">
                    <div>
                        <h1 class="display-lg text-[#303334] mb-2 font-black">Importar Productos</h1>
                        <p class="body-md text-[#5d5f60]">Carga masiva de inventario mediante archivo Excel o CSV.</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-sm font-bold text-[#5d5f60] hover:text-[#be004c] transition-colors">
                        &larr; Volver
                    </a>
                </header>

                @if(session('error'))
                    <div class="mb-8 p-4 bg-red-50 text-red-600 rounded-xl body-md font-medium border-l-4 border-red-500">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="md:col-span-2">
                        <div class="card bg-white p-10 rounded-2xl shadow-sm border-none mb-8">
                            <form action="{{ route('products.import.preview') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-8">
                                    <label class="block headline-md text-lg text-[#303334] mb-6">Selecciona tu archivo</label>
                                    <div class="relative group">
                                        <input type="file" name="file" id="file" class="hidden" required accept=".xlsx,.xls,.csv">
                                        <label for="file" class="flex flex-col items-center justify-center border-2 border-dashed border-[#5d5f60]/20 rounded-2xl py-12 px-6 bg-[#faf9f9] group-hover:bg-[#ffd9e2]/10 group-hover:border-[#be004c]/30 transition-all cursor-pointer">
                                            <div class="w-16 h-16 bg-[#be004c]/10 text-[#be004c] rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                            </div>
                                            <span class="body-md text-[#303334] font-bold mb-1" id="file-name">Arrastrar archivo o clic aquí</span>
                                            <span class="label-md text-xs text-[#5d5f60]">Formatos soportados: XLSX, XLS, CSV (Max 2MB)</span>
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-4 bg-[#be004c] text-white rounded-xl headline-md text-lg hover:scale-[1.02] transition-transform shadow-lg shadow-[#be004c]/20">
                                    Subir y Validar
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <h3 class="headline-md text-base text-[#303334] mb-4">Instrucciones</h3>
                            <ul class="space-y-4 body-md text-[#5d5f60] text-sm">
                                <li class="flex items-start">
                                    <span class="w-5 h-5 rounded-full bg-[#be004c] text-white flex items-center justify-center text-[10px] mr-3 mt-0.5">1</span>
                                    <span>Usa la plantilla oficial para evitar errores de formato.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-5 h-5 rounded-full bg-[#be004c] text-white flex items-center justify-center text-[10px] mr-3 mt-0.5">2</span>
                                    <span>Las columnas requeridas son: <strong>Nombre producto</strong>, <strong>precio compra</strong>, <strong>precio venta</strong> y <strong>cantidad</strong>.</span>
                                </li>
                            </ul>
                        </div>

                        <div class="card bg-[#ffd9e2]/30 p-6 rounded-2xl border-none">
                            <h4 class="label-md text-[#be004c] font-bold mb-3 uppercase tracking-widest">Recursos</h4>
                            <p class="body-md text-xs text-[#be004c] mb-6">Descarga una plantilla de ejemplo para asegurar que tus datos se carguen correctamente.</p>
                            <a href="{{ route('products.import.template') }}" class="inline-flex items-center px-4 py-2 bg-[#be004c] text-white rounded-lg text-xs font-bold hover:bg-[#be004c]/90 transition-colors">
                                Descargar Plantilla
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.getElementById('file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'Arrastrar archivo o clic aquí';
    document.getElementById('file-name').textContent = fileName;
});
</script>
@endsection
