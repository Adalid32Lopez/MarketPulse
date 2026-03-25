<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-tight">
            Nuevo Producto — <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">{{ $business->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Contenedor Principal Glassmorphism --}}
            <div class="bg-slate-900/50 border border-slate-800 backdrop-blur-xl p-8 rounded-3xl shadow-2xl relative overflow-hidden">
                {{-- Efecto de resplandor sutil en la esquina --}}
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-600/10 rounded-full blur-3xl"></div>
                
                <form method="POST" action="{{ route('businesses.products.store', $business) }}" class="relative z-10">
                    @csrf

                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-[0.2em] mb-8 border-b border-slate-800 pb-4">
                        Información General
                    </h3>

                    {{-- Nombre --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nombre del Producto *</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej. Laptop Pro 14"
                               class="w-full bg-slate-950/50 border-slate-800 rounded-xl text-slate-200 placeholder-slate-600 focus:border-blue-500/50 focus:ring-blue-500/20 transition-all duration-300 shadow-inner">
                        @error('name') <p class="text-red-400 text-xs mt-2 font-medium italic">× {{ $message }}</p> @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Descripción Detallada</label>
                        <textarea name="description" rows="3" placeholder="Describe las características principales..."
                                  class="w-full bg-slate-950/50 border-slate-800 rounded-xl text-slate-200 placeholder-slate-600 focus:border-blue-500/50 focus:ring-blue-500/20 transition-all duration-300 shadow-inner">{{ old('description') }}</textarea>
                    </div>

                    {{-- Precio y Stock --}}
                    <div class="mb-6 grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Precio ($) *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">$</span>
                                <input type="number" name="price" step="0.01" value="{{ old('price') }}"
                                       class="w-full pl-8 bg-slate-950/50 border-slate-800 rounded-xl text-emerald-400 font-mono font-bold focus:border-emerald-500/50 focus:ring-emerald-500/20 transition-all duration-300">
                            </div>
                            @error('price') <p class="text-red-400 text-xs mt-2 font-medium italic">× {{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Stock Inicial *</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                   class="w-full bg-slate-950/50 border-slate-800 rounded-xl text-slate-200 font-mono font-bold focus:border-blue-500/50 focus:ring-blue-500/20 transition-all duration-300">
                            @error('stock') <p class="text-red-400 text-xs mt-2 font-medium italic">× {{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- SKU y Categoría --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">SKU / Código</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" placeholder="PROD-001"
                                   class="w-full bg-slate-950/50 border-slate-800 rounded-xl text-slate-300 font-mono text-sm focus:border-blue-500/50 focus:ring-blue-500/20 transition-all duration-300">
                            @error('sku') <p class="text-red-400 text-xs mt-2 font-medium italic">× {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Categoría</label>
                            <select name="category_id" class="w-full bg-slate-950/50 border-slate-800 rounded-xl text-slate-300 focus:border-blue-500/50 focus:ring-blue-500/20 transition-all duration-300">
                                <option value="" class="bg-slate-900">Sin categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="bg-slate-900">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Activo con Switch Style --}}
                    <div class="mb-10 flex items-center p-4 bg-slate-950/30 rounded-2xl border border-slate-800/50">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ml-3 text-sm font-bold text-slate-400 uppercase tracking-tighter">Producto visible y activo</span>
                        </label>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-slate-800">
                        <a href="{{ route('businesses.products.index', $business) }}"
                           class="order-2 sm:order-1 text-center px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-300 transition-colors uppercase tracking-widest">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="order-1 sm:order-2 px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl font-black uppercase tracking-widest shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all active:scale-95">
                            Guardar Producto
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>