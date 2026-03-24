<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(!$business)
                <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-6">
                    No tienes ningún negocio registrado aún.
                    <a href="{{ route('businesses.create') }}" class="underline font-semibold">Crear negocio</a>
                </div>
            @else

                {{-- Navegación rápida al negocio --}}
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">{{ $business->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $business->industry ?? 'Sin industria' }} · {{ $business->currency }}</p>
                        </div>
                        <a href="{{ route('businesses.edit', $business) }}" class="text-sm text-blue-600 hover:underline">Editar negocio</a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <a href="{{ route('businesses.products.index', $business) }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <span class="text-2xl mb-1">📦</span>
                            <span class="text-sm font-medium">Productos</span>
                        </a>
                        <a href="{{ route('businesses.categories.index', $business) }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <span class="text-2xl mb-1">🏷️</span>
                            <span class="text-sm font-medium">Categorías</span>
                        </a>
                        <a href="{{ route('businesses.customers.index', $business) }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <span class="text-2xl mb-1">👥</span>
                            <span class="text-sm font-medium">Clientes</span>
                        </a>
                        <a href="{{ route('businesses.sales.create', $business) }}" class="flex flex-col items-center p-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <span class="text-2xl mb-1">🛒</span>
                            <span class="text-sm font-medium">Nueva Venta</span>
                        </a>
                        <a href="{{ route('businesses.sales.index', $business) }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <span class="text-2xl mb-1">📊</span>
                            <span class="text-sm font-medium">Ventas</span>
                        </a>
                        <a href="{{ route('businesses.reports.index', $business) }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <span class="text-2xl mb-1">📈</span>
                            <span class="text-sm font-medium">Reportes</span>
                        </a>
                        <a href="{{ route('businesses.alerts.index', $business) }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <span class="text-2xl mb-1">🔔</span>
                            <span class="text-sm font-medium">Alertas</span>
                        </a>
                        <a href="{{ route('businesses.users.index', $business) }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <span class="text-2xl mb-1">👤</span>
                            <span class="text-sm font-medium">Equipo</span>
                        </a>
                    </div>
                </div>

                {{-- Tarjetas de métricas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <p class="text-sm text-gray-500">Ventas este mes</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $salesThisMonth }}</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <p class="text-sm text-gray-500">Ingresos este mes</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">${{ number_format($revenueThisMonth, 2) }}</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <p class="text-sm text-gray-500">Ingresos hoy</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1">${{ number_format($revenueToday, 2) }}</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <p class="text-sm text-gray-500">Total clientes</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalCustomers }}</p>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <p class="text-sm text-gray-500">Alertas sin leer</p>
                        <p class="text-3xl font-bold {{ $unreadAlerts > 0 ? 'text-red-500' : 'text-gray-800' }} mt-1">
                            {{ $unreadAlerts }}
                        </p>
                        <a href="{{ route('businesses.alerts.index', $business) }}" class="text-xs text-blue-600 hover:underline mt-1 block">Ver alertas</a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Últimas ventas --}}
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b flex justify-between items-center">
                            <h3 class="font-semibold text-gray-700">Últimas ventas</h3>
                            <a href="{{ route('businesses.sales.index', $business) }}" class="text-sm text-blue-600 hover:underline">Ver todas</a>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-xs text-gray-500">Código</th>
                                    <th class="px-6 py-3 text-xs text-gray-500">Cliente</th>
                                    <th class="px-6 py-3 text-xs text-gray-500">Total</th>
                                    <th class="px-6 py-3 text-xs text-gray-500">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSales as $sale)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-3 font-mono text-sm">
                                            <a href="{{ route('businesses.sales.show', [$business, $sale]) }}" class="text-blue-600 hover:underline">
                                                {{ $sale->code }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-3 text-sm">{{ $sale->customer->name ?? 'Sin cliente' }}</td>
                                        <td class="px-6 py-3 text-sm font-semibold">${{ number_format($sale->total, 2) }}</td>
                                        <td class="px-6 py-3 text-sm">
                                            @php
                                                $colors = [
                                                    'COMPLETED' => 'text-green-600',
                                                    'PENDING' => 'text-yellow-600',
                                                    'CANCELLED' => 'text-red-500',
                                                    'REFUNDED' => 'text-gray-500',
                                                ];
                                            @endphp
                                            <span class="{{ $colors[$sale->status] ?? '' }}">{{ $sale->status }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-6 text-center text-gray-400 text-sm">
                                            No hay ventas aún.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Productos con stock bajo --}}
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b flex justify-between items-center">
                            <h3 class="font-semibold text-gray-700">⚠️ Stock bajo</h3>
                            <a href="{{ route('businesses.products.index', $business) }}" class="text-sm text-blue-600 hover:underline">Ver productos</a>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-xs text-gray-500">Producto</th>
                                    <th class="px-6 py-3 text-xs text-gray-500">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts as $product)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-6 py-3 text-sm">{{ $product->name }}</td>
                                        <td class="px-6 py-3 text-sm font-semibold text-red-500">
                                            {{ $product->stock }} unidades
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-6 text-center text-gray-400 text-sm">
                                            Todos los productos tienen stock suficiente ✓
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif
        </div>
    </div>
</x-app-layout>