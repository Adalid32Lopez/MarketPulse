<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Productos de {{ $business->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensaje de éxito --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Botón crear --}}
            <div class="mb-4 flex justify-end">
                <a href="{{ route('businesses.products.create', $business) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nuevo Producto
                </a>
            </div>

            {{-- Tabla --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">SKU</th>
                            <th class="px-6 py-3">Categoría</th>
                            <th class="px-6 py-3">Precio</th>
                            <th class="px-6 py-3">Stock</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $product->name }}</td>
                            <td class="px-6 py-4">{{ $product->sku ?? '—' }}</td>
                            <td class="px-6 py-4">{{ $product->category->name ?? '—' }}</td>
                            <td class="px-6 py-4">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4">{{ $product->stock }}</td>
                            <td class="px-6 py-4">
                                @if($product->is_active)
                                    <span class="text-green-600 font-semibold">Activo</span>
                                @else
                                    <span class="text-red-500">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 flex gap-2">
                                <a href="{{ route('businesses.products.edit', [$business, $product]) }}"
                                   class="text-blue-600 hover:underline">Editar</a>

                                <form method="POST"
                                      action="{{ route('businesses.products.destroy', [$business, $product]) }}"
                                      onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                No hay productos aún. ¡Crea el primero!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Paginación --}}
                <div class="px-6 py-4">
                    {{ $products->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
