<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $report->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Info del reporte --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <div class="grid grid-cols-3 gap-4 text-sm text-gray-600 mb-4">
                    <div>
                        <span class="font-medium">Tipo:</span>
                        <span class="ml-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                            {{ $report->type }}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium">Período:</span>
                        {{ $report->filters['date_from'] }} — {{ $report->filters['date_to'] }}
                    </div>
                    <div>
                        <span class="font-medium">Generado:</span>
                        {{ $report->generated_at?->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            {{-- Tarjetas de métricas --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="text-xs text-gray-500">Total ventas</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">
                        {{ $report->filters['total_sales'] }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="text-xs text-gray-500">Ingresos totales</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">
                        ${{ number_format($report->filters['total_revenue'], 2) }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="text-xs text-gray-500">Ticket promedio</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">
                        ${{ number_format($report->filters['avg_ticket'], 2) }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <p class="text-xs text-gray-500">Producto estrella</p>
                    <p class="text-lg font-bold text-gray-800 mt-1 truncate">
                        {{ $report->filters['top_product'] }}
                    </p>
                </div>
            </div>

            {{-- Tabla de productos vendidos --}}
            @if(!empty($report->filters['product_sales']))
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-700">Productos vendidos en el período</h3>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3">Producto</th>
                            <th class="px-6 py-3">Unidades vendidas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($report->filters['product_sales'] as $productName => $qty)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $productName }}</td>
                            <td class="px-6 py-3 font-semibold">{{ $qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <div class="flex justify-between">
                <a href="{{ route('businesses.reports.index', $business) }}"
                   class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">
                    ← Volver a reportes
                </a>

                <form method="POST"
                      action="{{ route('businesses.reports.destroy', [$business, $report]) }}"
                      onsubmit="return confirm('¿Eliminar este reporte?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 bg-red-100 text-red-600 rounded hover:bg-red-200">
                        Eliminar reporte
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>