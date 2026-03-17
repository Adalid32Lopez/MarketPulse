<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ventas de {{ $business->name }}
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
                <a href="{{ route('businesses.sales.create', $business) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Nueva Venta
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3">Código</th>
                            <th class="px-6 py-3">Cliente</th>
                            <th class="px-6 py-3">Vendedor</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-sm">{{ $sale->code }}</td>
                            <td class="px-6 py-4">{{ $sale->customer->name ?? 'Sin cliente' }}</td>
                            <td class="px-6 py-4">{{ $sale->seller->name }}</td>
                            <td class="px-6 py-4 font-semibold">${{ number_format($sale->total, 2) }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'COMPLETED' => 'text-green-600',
                                        'PENDING'   => 'text-yellow-600',
                                        'CANCELLED' => 'text-red-500',
                                        'REFUNDED'  => 'text-gray-500',
                                    ];
                                @endphp
                                <span class="{{ $colors[$sale->status] ?? '' }} font-semibold">
                                    {{ $sale->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $sale->sold_at?->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('businesses.sales.show', [$business, $sale]) }}"
                                   class="text-blue-600 hover:underline">Ver detalle</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                No hay ventas aún. ¡Registra la primera!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 py-4">
                    {{ $sales->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>