@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    @include('layouts.navigation')

    <!-- Main Content -->
    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-7xl mx-auto">
                <header class="mb-12 flex justify-between items-center">
                    <div>
                        <h1 class="display-lg text-[#303334] mb-2 font-black">Validación de Datos</h1>
                        <p class="body-md text-[#5d5f60]">Confirma que los datos del archivo son correctos antes de realizar la importación final.</p>
                    </div>
                    <form action="{{ route('products.import.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="path" value="{{ $path }}">
                        <input type="hidden" name="config" value="{{ $config }}">
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('products.import') }}" class="px-6 py-3 bg-white text-[#5d5f60] rounded-xl headline-md text-sm hover:bg-[#f3f3f4] transition-colors">
                                Volver y Cambiar
                            </a>
                            <button type="submit" class="px-8 py-3 bg-[#be004c] text-white rounded-xl headline-md text-sm hover:scale-[1.05] transition-transform shadow-lg shadow-[#be004c]/20">
                                Confirmar e Importar
                            </button>
                        </div>
                    </form>
                </header>

                <div class="card bg-white p-0 rounded-2xl shadow-sm border-none overflow-hidden mb-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#f3f3f4]">
                                    <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs">#</th>
                                    <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs">Nombre Producto</th>
                                    <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs">Categoría</th>
                                    <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs">Descripción</th>
                                    <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs">Costo</th>
                                    <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs">Venta</th>
                                    <th class="px-6 py-4 label-md uppercase tracking-widest text-[#5d5f60] text-xs">Stock</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#f3f3f4]">
                                @foreach($rows as $index => $row)
                                    <tr class="hover:bg-[#faf9f9] transition-colors">
                                        <td class="px-6 py-4 body-md text-xs text-[#5d5f60]">{{ $index + 2 }}</td> <!-- +2 accounting for header row and 0-index -->
                                        <td class="px-6 py-4 body-md text-[#303334] font-medium">{{ isset($mapping['name']) ? ($row[$mapping['name']] ?? 'N/A') : 'N/A' }}</td>
                                        <td class="px-6 py-4 body-md text-[#be004c]">{{ isset($mapping['category']) ? ($row[$mapping['category']] ?? '-') : '-' }}</td>
                                        <td class="px-6 py-4 body-md text-[#5d5f60] max-w-xs truncate">{{ isset($mapping['description']) ? ($row[$mapping['description']] ?? '-') : '-' }}</td>
                                        <td class="px-6 py-4 body-md text-[#303334]">${{ number_format(isset($mapping['purchase_price']) ? ($row[$mapping['purchase_price']] ?? 0) : 0, 2) }}</td>
                                        <td class="px-6 py-4 body-md text-[#be004c] font-bold">${{ number_format(isset($mapping['selling_price']) ? ($row[$mapping['selling_price']] ?? 0) : 0, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 bg-[#ffd9e2]/50 text-[#be004c] rounded-full text-xs font-bold">
                                                {{ isset($mapping['stock']) ? ($row[$mapping['stock']] ?? 0) : 0 }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
