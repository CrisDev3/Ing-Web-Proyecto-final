<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Auto-Matrícula
            </h2>

            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-3 rounded bg-green-100 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-3 rounded bg-red-100 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Card: Seleccionar materia --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Estudiante: <span class="font-semibold">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</span>
                        </p>
                    </div>

                    <form method="GET" action="{{ route('mi.matricular') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm mb-1 font-medium">Materia</label>
                            <select name="materia_id"
                                    class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                    required>
                                <option value="">Seleccione una materia</option>
                                @foreach($materias as $m)
                                    <option value="{{ $m->id }}" {{ (string)$materiaId === (string)$m->id ? 'selected' : '' }}>
                                        {{ $m->codigo }} - {{ $m->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 w-full">
                                Ver grupos
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabla: grupos --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                    <h3 class="font-semibold">Grupos disponibles</h3>

                    @if(!$materiaId)
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Selecciona una materia para ver sus grupos disponibles.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="text-left border-b dark:border-gray-700">
                                    <tr>
                                        <th class="py-2 pr-4">Código</th>
                                        <th class="py-2 pr-4">Docente</th>
                                        <th class="py-2 pr-4">Horario</th>
                                        <th class="py-2 pr-4">Cupo</th>
                                        <th class="py-2 text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($grupos as $g)
                                        <tr class="border-b dark:border-gray-700">
                                            <td class="py-2 pr-4">{{ $g->codigo }}</td>
                                            <td class="py-2 pr-4">
                                                {{ $g->docente ? $g->docente->nombre . ' ' . $g->docente->apellido : 'Sin asignar' }}
                                            </td>
                                            <td class="py-2 pr-4">
                                                @if($g->horarios->count())
                                                    <div class="space-y-1">
                                                        @foreach($g->horarios as $h)
                                                            <div class="text-xs">
                                                                {{ $h->dia }} {{ $h->hora_inicio }} - {{ $h->hora_fin }} ({{ $h->tipo }})
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-500">Sin horario</span>
                                                @endif
                                            </td>
                                            <td class="py-2 pr-4">
                                                {{ $g->cupo_actual }}/{{ $g->cupo_maximo }}
                                            </td>
                                            <td class="py-2 text-right">
                                                <form method="POST" action="{{ route('mi.matricular.store') }}">
                                                    @csrf
                                                    <input type="hidden" name="grupo_id" value="{{ $g->id }}">
                                                    <button class="px-3 py-1 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                                        Matricular
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-3 text-gray-500">
                                                No hay grupos con cupo disponibles para esta materia.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
