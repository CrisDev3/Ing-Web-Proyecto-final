<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Grupo: {{ $grupo->codigo }}
            </h2>

            <a href="{{ route('grupos.index') }}"
               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">Materia</div>
                    <div class="text-gray-900 dark:text-gray-100 font-medium">
                        {{ $grupo->materia?->codigo }} - {{ $grupo->materia?->nombre }}
                    </div>
                </div>

                <div>
                    <div class="text-gray-500">Docente</div>
                    <div class="text-gray-900 dark:text-gray-100 font-medium">
                        {{ $grupo->docente?->nombre ?? 'No asignado'}} {{ $grupo->docente?->apellido }}
                    </div>
                </div>

                <div>
                    <div class="text-gray-500">Período</div>
                    <div class="text-gray-900 dark:text-gray-100 font-medium">
                        {{ $grupo->periodo_academico }}
                    </div>
                </div>

                <div>
                    <div class="text-gray-500">Cupo</div>
                    <div class="text-gray-900 dark:text-gray-100 font-medium">
                        {{ $grupo->cupo_actual }} / {{ $grupo->cupo_maximo }}
                        <span class="text-gray-500">({{ $grupo->cupos_disponibles }} disponibles)</span>
                    </div>
                </div>

                <div>
                    <div class="text-gray-500">Activo</div>
                    <div class="text-gray-900 dark:text-gray-100 font-medium">
                        {{ $grupo->activo ? 'Sí' : 'No' }}
                    </div>
                </div>
            </div>

            <div class="pt-2 flex gap-2">
                <a href="{{ route('grupos.edit', $grupo) }}"
                   class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                    Editar
                </a>

                <form method="POST" action="{{ route('grupos.destroy', $grupo) }}"
                      onsubmit="return confirm('¿Seguro que deseas eliminar este grupo?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>

        {{-- Horarios (si ya los tienes relacionados) --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <h3 class="font-semibold mb-4 text-gray-900 dark:text-gray-100">Horarios asignados</h3>

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b dark:border-gray-700">
                        <th class="py-2">Día</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($grupo->horarios as $h)
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-2">{{ $h->dia }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($h->hora_inicio)->format('H:i') }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($h->hora_fin)->format('H:i') }}</td>
                            <td>{{ $h->tipo }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-3 text-gray-500">Este grupo no tiene horarios asignados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <p class="text-xs text-gray-500 mt-3">
                (Luego agregamos la pantalla para asignar/remover horarios.)
            </p>
        </div>

    </div>
</x-app-layout>
