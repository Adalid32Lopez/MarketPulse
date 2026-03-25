<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-tight">
            Productos de <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">{{ $business->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensaje de éxito con efecto Glass --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/50 text-emerald-400 rounded-2xl backdrop-blur-md flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Barra de acciones --}}
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-slate-400 font-medium italic text-sm">Gestiona el inventario de tu negocio</h3>
                
                <a href="{{ route('businesses.products.create', $business) }}"
                   class="group relative inline-flex items-center justify-center px-6 py-2.5 font-bold text-white transition-all duration-200 bg-blue-600 rounded-xl hover:bg-blue-500 shadow-lg shadow-blue-500/20">
                    <span class="mr-2 group-hover:rotate-90 transition-transform duration-300">+</span>
                    Nuevo Producto
                </a>
            </div>

            {{-- Tabla con Estilo Premium Dark --}}
            <div class="bg-slate-900/50 border border-slate-800 backdrop-blur-xl rounded-3xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900/80 border-b border-slate-800">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-500">Nombre</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-500">SKU</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-500">Categoría</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-500 text-right">Precio</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Stock</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-500 text-center">Estado</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-widest text-slate-500 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @forelse($products as $product)
                            <tr class="hover:bg-slate-800/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-200 group-hover:text-blue-400 transition-colors">
                                        {{ $product->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                    {{ $product->sku ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-slate-800 text-slate-400 rounded-lg text-xs font-medium border border-slate-700">
                                        {{ $product->category->name ?? 'Sin categoría' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-white">
                                    ${{ number_format($product->price, 2) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold {{ $product->stock <= 5 ? 'text-red-400' : 'text-slate-300' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($product->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black border border-emerald-500/20 bg-emerald-500/10 text-emerald-500 uppercase tracking-tighter">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black border border-slate-700 bg-slate-800 text-slate-500 uppercase tracking-tighter">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-4">
                                        <a href="{{ route('businesses.products.edit', [$business, $product]) }}"
                                           class="text-slate-400 hover:text-blue-400 transition-colors transform hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('businesses.products.destroy', [$business, $product]) }}"
                                              onsubmit="return confirm('¿Eliminar este producto?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-slate-600 hover:text-red-500 transition-colors transform hover:scale-110">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="text-4xl opacity-20">📦</span>
                                        <span class="text-slate-500 font-medium">No hay productos en el inventario aún.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación personalizada Dark --}}
                @if($products->hasPages())
                    <div class="px-8 py-6 border-t border-slate-800 bg-slate-900/30">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>