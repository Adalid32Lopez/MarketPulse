<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Generar Reporte — {{ $business->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">

                <form method="POST" action="{{ route('businesses.reports.store', $business) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Título del reporte *</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               placeholder="Ej: Ventas de marzo 2026"
                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tipo *</label>
                        <select name="type" id="type" onchange="setDates(this.value)"
                                class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="DAILY">Diario</option>
                            <option value="WEEKLY">Semanal</option>
                            <option value="MONTHLY" selected>Mensual</option>
                            <option value="CUSTOM">Personalizado</option>
                        </select>
                    </div>

                    <div class="mb-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Desde *</label>
                            <input type="date" name="date_from" id="date_from"
                                   value="{{ old('date_from') }}"
                                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @error('date_from') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hasta *</label>
                            <input type="date" name="date_to" id="date_to"
                                   value="{{ old('date_to') }}"
                                   class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            @error('date_to') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('businesses.reports.index', $business) }}"
                           class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Generar Reporte
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        // Al cargar la página, setear fechas del mes actual
        setDates('MONTHLY');

        function setDates(type) {
            const today = new Date();
            let from, to;

            if (type === 'DAILY') {
                from = to = today.toISOString().split('T')[0];
            } else if (type === 'WEEKLY') {
                const day = today.getDay();
                const diffFrom = today.getDate() - day + (day === 0 ? -6 : 1);
                from = new Date(today.setDate(diffFrom)).toISOString().split('T')[0];
                to   = new Date(new Date().setDate(diffFrom + 6)).toISOString().split('T')[0];
            } else if (type === 'MONTHLY') {
                const y = today.getFullYear();
                const m = String(today.getMonth() + 1).padStart(2, '0');
                from = `${y}-${m}-01`;
                const lastDay = new Date(y, today.getMonth() + 1, 0).getDate();
                to   = `${y}-${m}-${lastDay}`;
            } else {
                // CUSTOM: no cambiar fechas
                return;
            }

            document.getElementById('date_from').value = from;
            document.getElementById('date_to').value   = to;
        }
    </script>
</x-app-layout>