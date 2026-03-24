<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Invitar Vendedor — {{ $business->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">

                <p class="text-sm text-gray-500 mb-6">
                    El usuario debe tener una cuenta registrada en el sistema.
                    Ingresa su email para agregarlo como vendedor de este negocio.
                </p>

                <form method="POST" action="{{ route('businesses.users.store', $business) }}">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Email del usuario *</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="vendedor@ejemplo.com"
                               class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('businesses.users.index', $business) }}"
                           class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Invitar Vendedor
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>