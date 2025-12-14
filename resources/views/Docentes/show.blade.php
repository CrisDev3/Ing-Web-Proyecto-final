<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Docente: {{ $docente->nombre }} {{ $docente->apellido }}
            </h2>
            <a href="{{ route('docentes.index') }}"
               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500">Cédula</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $docente->cedula }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Email (login)</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $docente->email }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Especialidad</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $docente->especialidad ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Teléfono</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $docente->telefono ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Activo</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $docente->activo ? 'Sí' : 'No' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500">Usuario vinculado</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $docente->usuario ? 'Sí' : 'No' }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('docentes.edit', $docente) }}"
                       class="px-4 py-2 rounded bg-blue-600 text-white">
                        Editar
                    </a>

                    <form method="POST" action="{{ route('docentes.toggle-activo', $docente) }}">
                        @csrf
                        <button class="px-4 py-2 rounded bg-gray-700 text-white" type="submit">
                            {{ $docente->activo ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <h3 class="font-semibold mb-4 text-gray-900 dark:text-gray-100">Grupos asignados</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left border-b dark:border-gray-700">
                            <tr>
                                <th class="py-2 pr-4">Código</th>
                                <th class="py-2 pr-4">Materia</th>
                                <th class="py-2 pr-4">Periodo</th>
                                <th class="py-2 pr-4">Cupo</th>
                                <th class="py-2 pr-4">Activo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($docente->grupos as $g)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="py-2 pr-4">{{ $g->codigo }}</td>
                                    <td class="py-2 pr-4">
                                        {{ $g->materia?->codigo }} - {{ $g->materia?->nombre }}
                                    </td>
                                    <td class="py-2 pr-4">{{ $g->periodo_academico }}</td>
                                    <td class="py-2 pr-4">{{ $g->cupo_actual }} / {{ $g->cupo_maximo }}</td>
                                    <td class="py-2 pr-4">{{ $g->activo ? 'Sí' : 'No' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-3 text-gray-500">No hay grupos asignados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
