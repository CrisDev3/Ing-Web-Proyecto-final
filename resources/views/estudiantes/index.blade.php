{{-- resources/views/estudiantes/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Gestión de Estudiantes
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Administra la información de los estudiantes del sistema
                </p>
            </div>

            <a href="{{ route('estudiantes.create') }}"
               class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                + Nuevo Estudiante
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alerts --}}
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-100 text-green-900 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-100 text-red-900 px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Filtros --}}
                    <form action="{{ route('estudiantes.index') }}" method="GET"
                          class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                        <div class="md:col-span-5">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Búsqueda
                            </label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Buscar por nombre, cédula o email..."
                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                       focus:border-indigo-500 focus:ring-indigo-500"
                            />
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Plan de estudios
                            </label>
                            <select name="plan_id"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos los planes</option>
                                @foreach($planes as $plan)
                                    <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Estado
                            </label>
                            <select name="activo"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900
                                           focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                                <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 flex gap-2">
                            <button type="submit"
                                    class="w-full inline-flex justify-center px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                                Filtrar
                            </button>
                            <a href="{{ route('estudiantes.index') }}"
                               class="w-full inline-flex justify-center px-4 py-2 rounded-md bg-gray-200 text-gray-900 hover:bg-gray-300
                                      dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700"></div>

                    {{-- Tabla --}}
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-gray-600 dark:text-gray-300">
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3 pr-4">ID</th>
                                    <th class="py-3 pr-4">Cédula</th>
                                    <th class="py-3 pr-4">Nombre</th>
                                    <th class="py-3 pr-4">Email</th>
                                    <th class="py-3 pr-4">Teléfono</th>
                                    <th class="py-3 pr-4">Plan</th>
                                    <th class="py-3 pr-4">Estado</th>
                                    <th class="py-3 pr-2 text-right">Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="text-gray-900 dark:text-gray-100">
                                @forelse($estudiantes as $estudiante)
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-3 pr-4">{{ $estudiante->id }}</td>

                                        <td class="py-3 pr-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded bg-gray-200 text-gray-900
                                                         dark:bg-gray-700 dark:text-gray-100">
                                                {{ $estudiante->cedula }}
                                            </span>
                                        </td>

                                        <td class="py-3 pr-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-semibold">
                                                    {{ strtoupper(substr($estudiante->nombre, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold">
                                                        {{ $estudiante->nombre_completo }}
                                                    </div>

                                                    @if($estudiante->usuario)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                            Usuario vinculado
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <td class="py-3 pr-4">
                                            <a class="text-indigo-600 hover:underline"
                                               href="mailto:{{ $estudiante->email }}">
                                                {{ $estudiante->email }}
                                            </a>
                                        </td>

                                        <td class="py-3 pr-4">
                                            {{ $estudiante->telefono ?? 'N/A' }}
                                        </td>

                                        <td class="py-3 pr-4">
                                            <div class="text-xs inline-flex items-center px-2 py-1 rounded bg-sky-100 text-sky-900
                                                        dark:bg-sky-900/40 dark:text-sky-100">
                                                {{ $estudiante->planEstudios->codigo ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $estudiante->planEstudios->nombre ?? 'Sin plan' }}
                                            </div>
                                        </td>

                                        <td class="py-3 pr-4">
                                            @if($estudiante->activo)
                                                <span class="inline-flex items-center px-2 py-1 rounded bg-green-100 text-green-900
                                                             dark:bg-green-900/40 dark:text-green-100">
                                                    Activo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded bg-red-100 text-red-900
                                                             dark:bg-red-900/40 dark:text-red-100">
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-3 pr-2">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('estudiantes.show', $estudiante) }}"
                                                   class="px-3 py-1 rounded bg-gray-200 text-gray-900 hover:bg-gray-300
                                                          dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                                                    Ver
                                                </a>

                                                <a href="{{ route('estudiantes.edit', $estudiante) }}"
                                                   class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700">
                                                    Editar
                                                </a>

                                                <a href="{{ route('estudiantes.horario', $estudiante) }}"
                                                   class="px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                                    Horario
                                                </a>

                                                <form action="{{ route('estudiantes.toggle-activo', $estudiante) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            class="px-3 py-1 rounded
                                                                   {{ $estudiante->activo
                                                                        ? 'bg-yellow-500 text-white hover:bg-yellow-600'
                                                                        : 'bg-green-600 text-white hover:bg-green-700' }}">
                                                        {{ $estudiante->activo ? 'Desactivar' : 'Activar' }}
                                                    </button>
                                                </form>

                                                <form action="{{ route('estudiantes.destroy', $estudiante) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('¿Seguro que deseas eliminar este estudiante?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-10 text-center text-gray-500 dark:text-gray-400">
                                            No se encontraron estudiantes.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-4">
                        {{ $estudiantes->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
