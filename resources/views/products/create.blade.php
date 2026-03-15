<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo Producto — {{ $business->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">

                <form method="POST" action="{{ route('businesses.products.store', $business) }}">
                    @csrf

                    {{-- Nombre --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="description" rows="3"
                                  class="mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('description') }}</textarea>
                    </div>

                    {{-- Precio y Stock --}}
                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio *</label>
                            <input type="number" name="price" step="0.01" value="{{ old('price') }}"
                                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stock *</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- SKU --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('sku') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Categoría --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Categoría</label>
                        <select name="category_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Sin categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Activo --}}
                    <div class="mb-6 flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300">
                        <label class="text-sm text-gray-700">Producto activo</label>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('businesses.products.index', $business) }}"
                           class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Guardar Producto
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>