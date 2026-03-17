<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Venta {{ $sale->code }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Encabezado de la venta --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Código</p>
                        <p class="font-mono font-semibold">{{ $sale->code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Estado</p>
                        <p class="font-semibold">{{ $sale->status }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Cliente</p>
                        <p>{{ $sale->customer->name ?? 'Sin cliente' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Vendedor</p>
                        <p>{{ $sale->seller->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Método de pago</p>
                        <p>{{ $sale->payment_method }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Fecha</p>
                        <p>{{ $sale->sold_at?->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($sale->notes)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Notas</p>
                        <p>{{ $sale->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Items de la venta --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3">Producto</th>
                            <th class="px-6 py-3">Cantidad</th>
                            <th class="px-6 py-3">Precio unitario</th>
                            <th class="px-6 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                        <tr class="border-b">
                            <td class="px-6 py-4">{{ $item->product->name }}</td>
                            <td class="px-6 py-4">{{ $item->quantity }}</td>
                            <td class="px-6 py-4">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-6 py-4">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Totales --}}
                <div class="px-6 py-4 text-right space-y-1">
                    <p class="text-gray-600">Descuento: -${{ number_format($sale->discount, 2) }}</p>
                    <p class="text-gray-600">Impuesto: +${{ number_format($sale->tax, 2) }}</p>
                    <p class="text-xl font-bold">Total: ${{ number_format($sale->total, 2) }}</p>
                </div>
            </div>

            {{-- Cambiar estado --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-700 mb-3">Cambiar estado</h3>
                <form method="POST" action="{{ route('businesses.sales.update', [$business, $sale]) }}"
                      class="flex gap-3 items-end">
                    @csrf
                    @method('PUT')
                    <div class="flex-1">
                        <select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                            @foreach(['PENDING', 'COMPLETED', 'CANCELLED', 'REFUNDED'] as $status)
                                <option value="{{ $status }}" {{ $sale->status === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Actualizar estado
                    </button>
                </form>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('businesses.sales.index', $business) }}"
                   class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">
                    ← Volver a ventas
                </a>
                <form method="POST" action="{{ route('businesses.sales.destroy', [$business, $sale]) }}"
                      onsubmit="return confirm('¿Eliminar esta venta?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 bg-red-100 text-red-600 rounded hover:bg-red-200">
                        Eliminar venta
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>