<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(!$business)
                <div class="relative overflow-hidden bg-amber-900/20 border border-amber-500/50 text-amber-200 p-6 rounded-2xl backdrop-blur-md mb-8">
                    <div class="flex items-center gap-4">
                        <span class="text-3xl">⚠️</span>
                        <div>
                            <p class="font-medium">No tienes ningún negocio registrado aún.</p>
                            <a href="{{ route('businesses.create') }}" class="inline-block mt-2 px-4 py-2 bg-amber-500 text-amber-950 rounded-lg font-bold hover:bg-amber-400 transition-colors">
                                Crear negocio ahora
                            </a>
                        </div>
                    </div>
                </div>
            @else

                {{-- Selector de negocio (solo si tiene más de uno) --}}
                @if($allBusinesses->count() > 1)
                    <div class="mb-6 flex items-center gap-3">
                        <span class="text-slate-400 text-sm">Negocio activo:</span>
                        <form method="GET" action="{{ route('dashboard') }}">
                            <select name="business_id" onchange="this.form.submit()"
                                    class="bg-slate-800 border border-slate-700 text-slate-200 rounded-xl px-4 py-2 text-sm">
                                @foreach($allBusinesses as $b)
                                    <option value="{{ $b->id }}" {{ $b->id === $business->id ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                @endif

                {{-- Navegación rápida con Efecto Glass y Hover Elevado --}}
                <div class="bg-slate-900/50 border border-slate-800 backdrop-blur-xl rounded-3xl p-8 mb-8 shadow-2xl">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <h3 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">
                                {{ $business->name }}
                            </h3>
                            <p class="text-slate-400 mt-1 flex items-center gap-2">
                                <span class="px-2 py-0.5 bg-slate-800 rounded text-xs uppercase tracking-wider font-semibold text-slate-300">
                                    {{ $business->industry ?? 'Sin industria' }}
                                </span>
                                <span class="text-slate-600">|</span>
                                <span class="font-mono text-emerald-500">{{ $business->currency }}</span>
                            </p>
                        </div>
                        <a href="{{ route('businesses.edit', $business) }}" class="px-4 py-2 bg-slate-800 text-slate-300 rounded-xl border border-slate-700 hover:bg-slate-700 hover:text-white transition-all duration-300 text-sm">
                            Configuración del negocio
                        </a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                        @php
                        $navItems = [
                            ['route' => 'businesses.customers.index', 'icon' => '👥', 'label' => 'Clientes', 'color' => 'hover:border-pink-500/50'],
                            ['route' => 'businesses.sales.create', 'icon' => '🛒', 'label' => 'Venta', 'color' => 'bg-blue-600 hover:bg-blue-500 border-none shadow-lg shadow-blue-500/20', 'isPrimary' => true],
                            ['route' => 'businesses.sales.index', 'icon' => '📊', 'label' => 'Ventas', 'color' => 'hover:border-emerald-500/50'],
                            ];

                            // Solo admin ve estas secciones
                            if(auth()->user()->hasRole('admin')) {
                                $navItems = array_merge([
                                    ['route' => 'businesses.products.index', 'icon' => '📦', 'label' => 'Productos', 'color' => 'hover:border-blue-500/50'],
                                    ['route' => 'businesses.categories.index', 'icon' => '🏷️', 'label' => 'Categorías', 'color' => 'hover:border-purple-500/50'],
                                ], $navItems, [
                                    ['route' => 'businesses.reports.index', 'icon' => '📈', 'label' => 'Reportes', 'color' => 'hover:border-orange-500/50'],
                                    ['route' => 'businesses.alerts.index', 'icon' => '🔔', 'label' => 'Alertas', 'color' => 'hover:border-red-500/50'],
                                    ['route' => 'businesses.users.index', 'icon' => '👤', 'label' => 'Equipo', 'color' => 'hover:border-indigo-500/50'],
                                ]);
                            }
                        @endphp

                        @foreach($navItems as $item)
                            <a href="{{ route($item['route'], $business) }}" 
                               class="group flex flex-col items-center justify-center p-4 rounded-2xl border border-slate-800 bg-slate-900/40 transition-all duration-300 hover:-translate-y-1 {{ $item['color'] }}">
                                <span class="text-2xl mb-2 group-hover:scale-110 transition-transform">{{ $item['icon'] }}</span>
                                <span class="text-xs font-semibold {{ isset($item['isPrimary']) ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}">
                                    {{ $item['label'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Métricas con gradientes y resplandor --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <p class="text-sm text-gray-500">Ticket promedio</p>
                    <p class="text-3xl font-bold text-purple-600 mt-1">${{ number_format($avgTicket, 2) }}</p>
                </div>
                    @php
                        $metrics = [
                            ['label' => 'Ventas mes', 'value' => $salesThisMonth, 'color' => 'text-white'],
                            ['label' => 'Ingresos mes', 'value' => '$'.number_format($revenueThisMonth, 2), 'color' => 'text-emerald-400'],
                            ['label' => 'Ingresos hoy', 'value' => '$'.number_format($revenueToday, 2), 'color' => 'text-blue-400'],
                            ['label' => 'Clientes', 'value' => $totalCustomers, 'color' => 'text-purple-400'],
                        ];
                    @endphp

                    @foreach($metrics as $metric)
                        <div class="bg-slate-900 border border-slate-800 p-6 rounded-3xl shadow-xl">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $metric['label'] }}</p>
                            <p class="text-3xl font-black mt-2 {{ $metric['color'] }}">{{ $metric['value'] }}</p>
                        </div>
                    @endforeach

                    <div class="bg-slate-900 border border-slate-800 p-6 rounded-3xl shadow-xl relative overflow-hidden group">
                        <div class="relative z-10">
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Alertas</p>
                            <p class="text-3xl font-black mt-2 {{ $unreadAlerts > 0 ? 'text-red-500' : 'text-slate-300' }}">
                                {{ $unreadAlerts }}
                            </p>
                            <a href="{{ route('businesses.alerts.index', $business) }}" class="text-xs text-blue-400 hover:text-blue-300 mt-2 block font-medium uppercase tracking-tighter">Gestionar →</a>
                        </div>
                        @if($unreadAlerts > 0)
                            <div class="absolute -right-2 -bottom-2 text-6xl opacity-10 group-hover:opacity-20 transition-opacity">🔔</div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Tabla: Últimas ventas --}}
                    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                        <div class="px-8 py-6 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
                            <h3 class="font-bold text-slate-200 flex items-center gap-2">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                Últimas ventas
                            </h3>
                            <a href="{{ route('businesses.sales.index', $business) }}" class="text-xs font-bold text-blue-400 hover:underline tracking-widest uppercase">Ver historial</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-slate-500 border-b border-slate-800/50">
                                        <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider">Código</th>
                                        <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider">Cliente</th>
                                        <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-right">Total</th>
                                        <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-center">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/50">
                                    @forelse($recentSales as $sale)
                                        <tr class="hover:bg-slate-800/30 transition-colors group">
                                            <td class="px-8 py-4 font-mono text-sm">
                                                <a href="{{ route('businesses.sales.show', [$business, $sale]) }}" class="text-blue-400 group-hover:text-blue-300 font-bold">
                                                    #{{ $sale->code }}
                                                </a>
                                            </td>
                                            <td class="px-8 py-4 text-sm text-slate-300">{{ $sale->customer->name ?? 'Invitado' }}</td>
                                            <td class="px-8 py-4 text-sm font-bold text-white text-right">${{ number_format($sale->total, 2) }}</td>
                                            <td class="px-8 py-4 text-center">
                                                @php
                                                    $statusClasses = [
                                                        'COMPLETED' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                                        'PENDING' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                                        'CANCELLED' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                                    ];
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-[10px] font-black border {{ $statusClasses[$sale->status] ?? 'bg-slate-700 text-slate-300' }}">
                                                    {{ $sale->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-8 py-12 text-center text-slate-600 italic">No hay transacciones recientes.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tabla: Stock bajo --}}
                    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl">
                        <div class="px-8 py-6 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
                            <h3 class="font-bold text-slate-200 flex items-center gap-2">
                                <span class="text-red-500">⚠️</span> Alerta de Stock
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-slate-500 border-b border-slate-800/50">
                                        <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider">Producto</th>
                                        <th class="px-8 py-4 text-xs font-bold uppercase tracking-wider text-right">Stock Actual</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/50">
                                    @forelse($lowStockProducts as $product)
                                        <tr class="hover:bg-red-500/5 transition-colors">
                                            <td class="px-8 py-4 text-sm text-slate-300 font-medium">{{ $product->name }}</td>
                                            <td class="px-8 py-4 text-right">
                                                <span class="text-sm font-black text-red-400 bg-red-400/10 px-3 py-1 rounded-lg">
                                                    {{ $product->stock }} <small class="font-normal opacity-70">unidades</small>
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-8 py-12 text-center">
                                                <div class="flex flex-col items-center gap-2">
                                                    <span class="text-3xl">✅</span>
                                                    <span class="text-slate-500 text-sm">Inventario optimizado</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>