@extends('layouts.base')

@section('body')
<div class="min-h-full flex flex-col md:flex-row overflow-hidden bg-[#faf9f9]">
    <!-- Sidebar -->
    @include('layouts.navigation')

    <!-- Main Content -->
    <div class="flex flex-col w-full md:w-0 flex-1 overflow-hidden">
        <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none py-12 px-12">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 space-y-6 md:space-y-0">
                    <div>
                        <h1 class="display-lg text-[#303334] mb-2 font-black tracking-tight">Categorías</h1>
                        <p class="body-md text-[#5d5f60]">Organiza tus productos por grupos lógicos.</p>
                    </div>
                    
                    <div class="w-full md:w-96">
                        <form action="{{ route('categories.index') }}" method="GET" class="relative group">
                            <input type="text" name="search" value="{{ $search ?? '' }}" 
                                   placeholder="Buscar categorías..." 
                                   class="input-field pl-12! focus:ring-2 focus:ring-[#be004c]/20 transition-all">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[#5d5f60] group-focus-within:text-[#be004c] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </form>
                    </div>
                </div>

                <a href="{{ route('categories.create') }}" class="fab group" title="Nueva Categoría">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="absolute right-full mr-4 bg-[#303334] text-white px-3 py-1.5 rounded-xl text-xs opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none shadow-lg">
                        Nueva Categoría
                    </span>
                </a>

                @if(session('success'))
                    <div class="mb-8 p-4 bg-[#ffd9e2] text-[#be004c] rounded-xl body-md font-medium animate-in fade-in slide-in-from-top-4 duration-300">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-8 p-4 bg-[#f97386]/10 text-[#f97386] rounded-xl body-md font-medium animate-in fade-in slide-in-from-top-4 duration-300">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($categories as $category)
                        <div class="card group hover:bg-[#f3f3f4] transition-all duration-300 cursor-default relative overflow-hidden">
                            <!-- Background Accent -->
                            <div class="absolute top-0 right-0 w-32 h-32 bg-[#ffd9e2]/20 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-[#be004c]/10 transition-colors"></div>
                            
                            <div class="relative flex flex-col h-full">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <!-- Status Bloom -->
                                        @if($category->is_active)
                                            <div class="relative flex items-center justify-center w-3 h-3">
                                                <div class="absolute w-full h-full bg-[#4ade80] rounded-full blur-xs"></div>
                                                <div class="relative w-1.5 h-1.5 bg-[#4ade80] rounded-full"></div>
                                            </div>
                                            <span class="text-[10px] font-bold text-[#4ade80] uppercase tracking-widest">Activa</span>
                                        @else
                                            <div class="relative flex items-center justify-center w-3 h-3">
                                                <div class="absolute w-full h-full bg-[#5d5f60]/40 rounded-full blur-xs"></div>
                                                <div class="relative w-1.5 h-1.5 bg-[#5d5f60]/60 rounded-full"></div>
                                            </div>
                                            <span class="text-[10px] font-bold text-[#5d5f60] uppercase tracking-widest">Inactiva</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('categories.edit', $category) }}" class="p-2 text-[#5d5f60] hover:text-[#be004c] hover:bg-white rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Estás seguro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-[#5d5f60] hover:text-[#f97386] hover:bg-white rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <h3 class="headline-md text-xl text-[#303334] mb-2 group-hover:text-[#be004c] transition-colors">{{ $category->name }}</h3>
                                <p class="body-md text-[#5d5f60] line-clamp-2 mb-6 flex-1 italic">
                                    {{ $category->description ?? 'Sin descripción disponible.' }}
                                </p>

                                <div class="pt-4 border-t border-[#303334]/5 flex items-center justify-between">
                                    <span class="text-[10px] font-medium uppercase tracking-widest text-[#5d5f60]/50">Slug: {{ $category->slug }}</span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest bg-[#ffd9e2] text-[#be004c] px-3 py-1.5 rounded-full">
                                        {{ $category->products_count ?? $category->products()->count() }} Productos
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full card h-64 flex flex-col items-center justify-center border-dashed border-2 border-[#303334]/10 bg-transparent space-y-4">
                            <p class="body-md text-[#5d5f60] italic">No se encontraron categorías.</p>
                            @if($search)
                                <a href="{{ route('categories.index') }}" class="text-[#be004c] hover:underline label-md">Limpiar búsqueda</a>
                            @endif
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $categories->links() }}
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
