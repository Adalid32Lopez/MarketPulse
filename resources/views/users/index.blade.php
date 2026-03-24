<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Equipo de {{ $business->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('businesses.users.create', $business) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Invitar Vendedor
                </a>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Rol</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Dueño --}}
                        <tr class="border-b bg-blue-50">
                            <td class="px-6 py-4 font-semibold">{{ $owner->name }}</td>
                            <td class="px-6 py-4">{{ $owner->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded-full font-semibold">
                                    Admin / Dueño
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-sm">—</td>
                        </tr>

                        {{-- Vendedores --}}
                        @forelse($members as $member)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $member->name }}</td>
                            <td class="px-6 py-4">{{ $member->email }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full font-semibold">
                                    {{ ucfirst($member->pivot->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST"
                                      action="{{ route('businesses.users.destroy', [$business, $member]) }}"
                                      onsubmit="return confirm('¿Quitar a {{ $member->name }} del negocio?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:underline text-sm">Quitar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                No hay vendedores aún. ¡Invita al primero!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>