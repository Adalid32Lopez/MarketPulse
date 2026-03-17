<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Negocios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('businesses.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nuevo Negocio
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($businesses as $business)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-bold text-lg text-gray-800">{{ $business->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $business->industry ?? 'Sin industria' }}</p>
                    <p class="text-sm text-gray-500">Moneda: {{ $business->currency }}</p>

                    <div class="mt-4 flex gap-2 flex-wrap">
                        <a href="{{ route('businesses.products.index', $business) }}"
                           class="text-xs px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Productos</a>
                        <a href="{{ route('businesses.categories.index', $business) }}"
                           class="text-xs px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Categorías</a>
                        <a href="{{ route('businesses.customers.index', $business) }}"
                           class="text-xs px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Clientes</a>
                        <a href="{{ route('businesses.sales.index', $business) }}"
                           class="text-xs px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Ventas</a>
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <a href="{{ route('businesses.edit', $business) }}"
                           class="text-blue-600 text-sm hover:underline">Editar</a>

                        <form method="POST" action="{{ route('businesses.destroy', $business) }}"
                              onsubmit="return confirm('¿Eliminar este negocio y todos sus datos?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 text-sm hover:underline">Eliminar</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center text-gray-400 py-12">
                    No tienes negocios aún.
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>