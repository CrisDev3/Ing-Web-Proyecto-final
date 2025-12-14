<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Docentes
            </h2>

            <a href="{{ route('docentes.create') }}"
               class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                + Nuevo Docente
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
            @endif

            {{-- Filtros --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <form method="GET" action="{{ route('docentes.index') }}"
                      class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Buscar</label>
                        <input name="search" value="{{ request('search') }}"
                               placeholder="Nombre, apellido, cédula, email, especialidad..."
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Estado</label>
                        <select name="activo"
                                class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            <option value="">Todos</option>
                            <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button class="px-4 py-2 rounded bg-blue-600 text-white">Filtrar</button>
                        <a href="{{ route('docentes.index') }}"
                           class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabla --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left border-b dark:border-gray-700">
                            <tr>
                                <th class="py-2 pr-4">Cédula</th>
                                <th class="py-2 pr-4">Nombre</th>
                                <th class="py-2 pr-4">Email</th>
                                <th class="py-2 pr-4">Especialidad</th>
                                <th class="py-2 pr-4">Activo</th>
                                <th class="py-2 pr-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($docentes as $d)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="py-2 pr-4">{{ $d->cedula }}</td>
                                    <td class="py-2 pr-4">{{ $d->nombre }} {{ $d->apellido }}</td>
                                    <td class="py-2 pr-4">{{ $d->email }}</td>
                                    <td class="py-2 pr-4">{{ $d->especialidad ?? '—' }}</td>
                                    <td class="py-2 pr-4">
                                        @if($d->activo)
                                            <span class="px-2 py-1 rounded bg-green-200 text-green-900">Sí</span>
                                        @else
                                            <span class="px-2 py-1 rounded bg-gray-200 text-gray-900">No</span>
                                        @endif
                                    </td>
                                    <td class="py-2 pr-4">
                                        <div class="flex justify-end gap-3">
                                            <a class="underline text-indigo-600" href="{{ route('docentes.show', $d) }}">Ver</a>
                                            <a class="underline text-blue-600" href="{{ route('docentes.edit', $d) }}">Editar</a>

                                            <form method="POST" action="{{ route('docentes.toggle-activo', $d) }}">
                                                @csrf
                                                <button class="underline text-gray-700 dark:text-gray-200" type="submit">
                                                    {{ $d->activo ? 'Desactivar' : 'Activar' }}
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('docentes.destroy', $d) }}"
                                                  onsubmit="return confirm('¿Seguro que deseas eliminar este docente?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="underline text-red-600" type="submit">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-4 text-gray-500">No hay docentes.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $docentes->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
