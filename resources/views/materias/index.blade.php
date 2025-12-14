<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                Materias
            </h2>

            <a href="{{ route('materias.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                + Nueva Materia
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow rounded">
            <table class="min-w-full text-sm">
                <thead class="border-b dark:border-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Código</th>
                        <th class="px-4 py-2 text-left">Nombre</th>
                        <th class="px-4 py-2 text-left">Créditos</th>
                        <th class="px-4 py-2 text-left">Planes</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materias as $materia)
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-2">{{ $materia->codigo }}</td>
                            <td class="px-4 py-2">{{ $materia->nombre }}</td>
                            <td class="px-4 py-2">{{ $materia->creditos }}</td>
                            <td class="px-4 py-2">
                                {{ $materia->planesEstudios->count() }}
                            </td>
                            <td class="px-4 py-2 space-x-2">
                                <a href="{{ route('materias.show', $materia) }}" class="text-indigo-600">Ver</a>
                                <a href="{{ route('materias.edit', $materia) }}" class="text-blue-600">Editar</a>
                                <form action="{{ route('materias.destroy', $materia) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('¿Eliminar materia?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                No hay materias registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4">
                {{ $materias->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
