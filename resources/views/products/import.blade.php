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

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <form action="{{ route('products.import.preview') }}" method="POST" enctype="multipart/form-data" id="importForm">
                            @csrf
                            
                            <div class="card bg-white p-8 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border-none mb-8">
                                <h3 class="headline-md text-xl text-[#303334] mb-2">Mapeo de Columnas</h3>
                                <p class="body-md text-[#5d5f60] mb-8">Indica la letra de la columna (ej. A, B, C, AA) donde se encuentra cada dato.</p>
                                
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="label-md block mb-2 uppercase tracking-wider text-[#303334] font-bold">Nombre *</label>
                                        <input type="text" name="col_name" value="A" required class="input-field uppercase text-center font-bold text-[#be004c]" placeholder="A">
                                        @error('col_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="label-md block mb-2 uppercase tracking-wider text-[#303334] font-bold">Descripción</label>
                                        <input type="text" name="col_description" value="B" class="input-field uppercase text-center font-bold text-[#be004c]" placeholder="B">
                                    </div>
                                    <div>
                                        <label class="label-md block mb-2 uppercase tracking-wider text-[#303334] font-bold">Categoría (Slug)</label>
                                        <input type="text" name="col_category" value="C" class="input-field uppercase text-center font-bold text-[#be004c]" placeholder="C">
                                    </div>
                                    <div>
                                        <label class="label-md block mb-2 uppercase tracking-wider text-[#303334] font-bold">P. Compra *</label>
                                        <input type="text" name="col_purchase_price" value="D" required class="input-field uppercase text-center font-bold text-[#be004c]" placeholder="D">
                                    </div>
                                    <div>
                                        <label class="label-md block mb-2 uppercase tracking-wider text-[#303334] font-bold">P. Venta *</label>
                                        <input type="text" name="col_selling_price" value="E" required class="input-field uppercase text-center font-bold text-[#be004c]" placeholder="E">
                                    </div>
                                    <div>
                                        <label class="label-md block mb-2 uppercase tracking-wider text-[#303334] font-bold">Stock *</label>
                                        <input type="text" name="col_stock" value="F" required class="input-field uppercase text-center font-bold text-[#be004c]" placeholder="F">
                                    </div>
                                </div>
                            </div>

                            <div class="card bg-white p-8 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border-none mb-8">
                                <h3 class="headline-md text-xl text-[#303334] mb-6">Selecciona tu archivo</h3>
                                <div class="relative group mb-6">
                                    <input type="file" name="file" id="file" class="hidden" required accept=".xlsx,.xls,.csv">
                                    <label for="file" class="flex flex-col items-center justify-center border-2 border-dashed border-[#5d5f60]/20 rounded-2xl py-12 px-6 bg-[#faf9f9] group-hover:bg-[#ffd9e2]/10 group-hover:border-[#be004c]/30 transition-all cursor-pointer">
                                        <div class="w-16 h-16 bg-[#be004c]/10 text-[#be004c] rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                        <span class="body-md text-[#303334] font-bold mb-1" id="file-name">Arrastrar archivo o clic aquí</span>
                                        <span class="label-md text-xs text-[#5d5f60]">XLSX, XLS, CSV (Max 2MB)</span>
                                    </label>
                                </div>
                                
                                <button type="submit" class="w-full py-4 bg-[#be004c] text-white rounded-xl headline-md text-lg hover:scale-[1.02] transition-transform shadow-lg shadow-[#be004c]/20 font-bold">
                                    Validar y Continuar
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <h3 class="headline-md text-base text-[#303334] mb-4">Instrucciones</h3>
                            <ul class="space-y-4 body-md text-[#5d5f60] text-sm">
                                <li class="flex items-start">
                                    <span class="w-5 h-5 rounded-full bg-[#be004c] text-white flex items-center justify-center text-[10px] mr-3 mt-0.5 shrink-0">1</span>
                                    <span>Verifica que las letras de las columnas coincidan con las de tu archivo.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-5 h-5 rounded-full bg-[#be004c] text-white flex items-center justify-center text-[10px] mr-3 mt-0.5 shrink-0">2</span>
                                    <span>Se asume que la <strong>primera fila</strong> de tu archivo contiene encabezados y será ignorada.</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-5 h-5 rounded-full bg-[#be004c] text-white flex items-center justify-center text-[10px] mr-3 mt-0.5 shrink-0">3</span>
                                    <span>Para la <strong>Categoría</strong>, usa el "slug" (ej. <code>tops</code>, <code>vestidos-largos</code>). Si no existe o se deja en blanco, el producto no tendrá categoría.</span>
                                </li>
                            </ul>
                        </div>

                        <div class="card bg-[#ffd9e2]/30 p-6 rounded-2xl border-none">
                            <h4 class="label-md text-[#be004c] font-bold mb-3 uppercase tracking-widest">Generar Plantilla</h4>
                            <p class="body-md text-xs text-[#be004c] mb-6">Modifica las columnas de la izquierda y descarga una plantilla personalizada lista para llenar.</p>
                            <button form="importForm" formaction="{{ route('products.import.template') }}" formmethod="GET" formnovalidate class="w-full relative inline-flex items-center justify-center px-4 py-3 bg-white text-[#be004c] rounded-lg text-sm font-bold shadow-sm hover:bg-[#faf9f9] transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Descargar Plantilla
                            </button>
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
