<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reportes de {{ $business->name }}
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
                <a href="{{ route('businesses.reports.create', $business) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Generar Reporte
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3">Título</th>
                            <th class="px-6 py-3">Tipo</th>
                            <th class="px-6 py-3">Generado por</th>
                            <th class="px-6 py-3">Fecha</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $report->title }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">
                                    {{ $report->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $report->generatedBy->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                {{ $report->generated_at?->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 flex gap-3">
                                <a href="{{ route('businesses.reports.show', [$business, $report]) }}"
                                   class="text-blue-600 hover:underline">Ver</a>

                                <form method="POST"
                                      action="{{ route('businesses.reports.destroy', [$business, $report]) }}"
                                      onsubmit="return confirm('¿Eliminar este reporte?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:underline">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                No hay reportes aún. ¡Genera el primero!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 py-4">
                    {{ $reports->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>