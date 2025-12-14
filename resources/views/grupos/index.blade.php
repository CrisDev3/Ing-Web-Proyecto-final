<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Grupos
            </h2>

            <a href="{{ route('grupos.create') }}"
               class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                + Nuevo Grupo
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        {{-- Filtros --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="GET" action="{{ route('grupos.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Buscar</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Código o período..."
                           class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Materia</label>
                    <select name="materia_id" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                        <option value="">Todas</option>
                        @foreach ($materias as $m)
                            <option value="{{ $m->id }}" {{ (string)request('materia_id') === (string)$m->id ? 'selected' : '' }}>
                                {{ $m->codigo }} - {{ $m->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Docente</label>
                    <select name="docente_id" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                        <option value="">Todos</option>
                        @foreach ($docentes as $d)
                            <option value="{{ $d->id }}" {{ (string)request('docente_id') === (string)$d->id ? 'selected' : '' }}>
                                {{ $d->nombre }} {{ $d->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Activo</label>
                    <select name="activo" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                        <option value="">Todos</option>
                        <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                        Filtrar
                    </button>
                    <a href="{{ route('grupos.index') }}"
                       class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabla --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left border-b dark:border-gray-700">
                            <th class="py-2">Código</th>
                            <th>Materia</th>
                            <th>Docente</th>
                            <th>Período</th>
                            <th>Cupo</th>
                            <th>Activo</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grupos as $g)
                            <tr class="border-b dark:border-gray-700">
                                <td class="py-2">{{ $g->codigo }}</td>
                                <td>
                                    {{ $g->materia?->codigo }} - {{ $g->materia?->nombre }}
                                </td>
                                <td>
                                    {{ $g->docente?->nombre }} {{ $g->docente?->apellido }}
                                </td>
                                <td>{{ $g->periodo_academico }}</td>
                                <td>
                                    {{ $g->cupo_actual }} / {{ $g->cupo_maximo }}
                                    <span class="text-gray-500">
                                        ({{ $g->cupos_disponibles }} disp.)
                                    </span>
                                </td>
                                <td>
                                    @if ($g->activo)
                                        <span class="px-2 py-1 rounded bg-green-200 text-green-900">Sí</span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-gray-200 text-gray-900">No</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('grupos.show', $g) }}"
                                           class="px-3 py-1 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                                            Ver
                                        </a>

                                        <a href="{{ route('grupos.edit', $g) }}"
                                           class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('grupos.destroy', $g) }}"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar este grupo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-gray-500">
                                    No hay grupos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $grupos->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
