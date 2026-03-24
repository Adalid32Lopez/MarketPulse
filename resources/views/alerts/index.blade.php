<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Alertas de {{ $business->name }}
            @if($unreadCount > 0)
                <span class="ml-2 px-2 py-0.5 bg-red-500 text-white text-sm rounded-full">
                    {{ $unreadCount }} sin leer
                </span>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Formulario para crear alerta manual --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-gray-700 mb-4">Crear alerta manual</h3>

                <form method="POST" action="{{ route('businesses.alerts.store', $business) }}">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo *</label>
                            <select name="type" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="LOW_STOCK">⚠️ Stock bajo</option>
                                <option value="SALES_GOAL">🎯 Meta de ventas</option>
                                <option value="REVENUE_DROP">📉 Caída de ingresos</option>
                                <option value="NEW_SALE">🛒 Nueva venta</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Umbral (opcional)</label>
                            <input type="number" name="threshold" step="0.01" min="0"
                                   placeholder="Ej: 5 unidades o $1000"
                                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Mensaje *</label>
                        <input type="text" name="message" value="{{ old('message') }}"
                               placeholder="Ej: El producto Laptop tiene solo 2 unidades en stock"
                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('message') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Crear Alerta
                        </button>
                    </div>
                </form>
            </div>

            {{-- Lista de alertas --}}
            <div class="space-y-3">
                @forelse($alerts as $alert)

                @php
                    $styles = [
                        'LOW_STOCK'     => ['bg' => 'bg-yellow-50 border-yellow-300', 'icon' => '⚠️', 'badge' => 'bg-yellow-100 text-yellow-800'],
                        'SALES_GOAL'    => ['bg' => 'bg-green-50 border-green-300',  'icon' => '🎯', 'badge' => 'bg-green-100 text-green-800'],
                        'REVENUE_DROP'  => ['bg' => 'bg-red-50 border-red-300',      'icon' => '📉', 'badge' => 'bg-red-100 text-red-800'],
                        'NEW_SALE'      => ['bg' => 'bg-blue-50 border-blue-300',    'icon' => '🛒', 'badge' => 'bg-blue-100 text-blue-800'],
                    ];
                    $style = $styles[$alert->type] ?? ['bg' => 'bg-gray-50 border-gray-300', 'icon' => '🔔', 'badge' => 'bg-gray-100 text-gray-800'];
                @endphp

                <div class="border rounded-lg p-4 {{ $style['bg'] }} {{ $alert->is_read ? 'opacity-60' : '' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">{{ $style['icon'] }}</span>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded {{ $style['badge'] }}">
                                        {{ $alert->type }}
                                    </span>
                                    @if(!$alert->is_read)
                                        <span class="text-xs font-semibold text-red-600">● Sin leer</span>
                                    @endif
                                </div>
                                <p class="text-gray-800">{{ $alert->message }}</p>
                                @if($alert->threshold)
                                    <p class="text-sm text-gray-500 mt-1">Umbral: {{ $alert->threshold }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $alert->triggered_at?->format('d/m/Y H:i') }}
                                    — por {{ $alert->user->name }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-2 ml-4">
                            @if(!$alert->is_read)
                                <form method="POST"
                                      action="{{ route('businesses.alerts.read', [$business, $alert]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-xs px-3 py-1 bg-white border rounded hover:bg-gray-50 text-gray-600">
                                        Marcar leída
                                    </button>
                                </form>
                            @endif

                            <form method="POST"
                                  action="{{ route('businesses.alerts.destroy', [$business, $alert]) }}"
                                  onsubmit="return confirm('¿Eliminar esta alerta?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs px-3 py-1 bg-white border border-red-200 rounded hover:bg-red-50 text-red-500">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @empty
                <div class="bg-white rounded-lg shadow-sm p-12 text-center text-gray-400">
                    No hay alertas registradas.
                </div>
                @endforelse
            </div>

            {{-- Paginación --}}
            <div class="mt-4">
                {{ $alerts->links() }}
            </div>

        </div>
    </div>
</x-app-layout>