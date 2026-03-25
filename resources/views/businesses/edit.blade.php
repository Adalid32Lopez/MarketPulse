<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Negocio — {{ $business->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">

                <form method="POST" action="{{ route('businesses.update', $business) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre del negocio *</label>
                        <input type="text" name="name" value="{{ old('name', $business->name) }}"
                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Industria</label>
                        <input type="text" name="industry" value="{{ old('industry', $business->industry) }}"
                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Moneda *</label>
                        <select name="currency" class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            
                            <option value="BOB" {{ old('currency', $business->currency) == 'BOB' ? 'selected' : '' }}>BOB — Boliviano</option>
                            
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('dashboard') }}"
                           class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Actualizar Negocio
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>