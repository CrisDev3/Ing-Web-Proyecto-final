<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Horario: {{ $horario->dia }} {{ $horario->hora_inicio }} - {{ $horario->hora_fin }} ({{ ucfirst($horario->tipo) }})
            </h2>
            <a href="{{ route('horarios.index') }}"
               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500">Día</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $horario->dia }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Inicio</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $horario->hora_inicio }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Fin</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $horario->hora_fin }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Tipo</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ ucfirst($horario->tipo) }}</div>
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    <a href="{{ route('horarios.edit', $horario) }}"
                       class="px-4 py-2 rounded bg-blue-600 text-white">
                        Editar
                    </a>
                    <form method="POST" action="{{ route('horarios.destroy', $horario) }}"
                          onsubmit="return confirm('¿Seguro que deseas eliminar este horario?')">
                        @csrf
                        @method('DELETE')
                        <button class="px-4 py-2 rounded bg-red-600 text-white" type="submit">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <h3 class="font-semibold mb-4 text-gray-900 dark:text-gray-100">
                    Grupos que usan este horario
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left border-b dark:border-gray-700">
                            <tr>
                                <th class="py-2 pr-4">Grupo</th>
                                <th class="py-2 pr-4">Materia</th>
                                <th class="py-2 pr-4">Docente</th>
                                <th class="py-2 pr-4">Periodo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($horario->grupos as $g)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="py-2 pr-4">{{ $g->codigo }}</td>
                                    <td class="py-2 pr-4">{{ $g->materia?->codigo }} - {{ $g->materia?->nombre }}</td>
                                    <td class="py-2 pr-4">{{ $g->docente?->nombre_completo ?? '—' }}</td>
                                    <td class="py-2 pr-4">{{ $g->periodo_academico }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-3 text-gray-500">No hay grupos asignados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
