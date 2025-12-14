<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $plan->nombre }} ({{ $plan->codigo }})
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Asignación de materias por semestre
                </p>
            </div>

            <a href="{{ route('plan-estudios.index') }}"
               class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
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

            {{-- Agregar materia --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold mb-4">Agregar materia al plan</h3>

                    <form method="POST" action="{{ route('plan-estudios.agregar-materia', $plan) }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        @csrf

                        <div class="md:col-span-2">
                            <label class="block text-sm mb-1">Materia</label>
                            <select name="materia_id" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                                @foreach ($materiasDisponibles as $m)
                                    <option value="{{ $m->id }}">{{ $m->codigo }} - {{ $m->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Semestre</label>
                            <input type="number" name="semestre" min="1" max="20"
                                   class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                                   value="1">
                        </div>

                        <div class="flex items-end gap-3">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="es_obligatoria" value="1">
                                <span class="text-sm">Obligatoria</span>
                            </label>

                            <button class="ml-auto px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Agregar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabla de materias asignadas --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold mb-4">Materias del plan</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left border-b dark:border-gray-700">
                                <tr>
                                    <th class="py-2 pr-4">Código</th>
                                    <th class="py-2 pr-4">Materia</th>
                                    <th class="py-2 pr-4">Semestre</th>
                                    <th class="py-2 pr-4">Obligatoria</th>
                                    <th class="py-2 pr-4">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($plan->materias as $materia)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="py-2 pr-4">{{ $materia->codigo }}</td>
                                        <td class="py-2 pr-4">{{ $materia->nombre }}</td>

                                        {{-- Editar pivot inline --}}
                                        <td class="py-2 pr-4">
                                            <form method="POST" action="{{ route('plan-estudios.actualizar-materia', [$plan, $materia]) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')

                                                <input type="number" name="semestre" min="1" max="20"
                                                       value="{{ $materia->pivot->semestre }}"
                                                       class="w-24 rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">

                                                <label class="inline-flex items-center gap-2">
                                                    <input type="checkbox" name="es_obligatoria" value="1"
                                                        {{ $materia->pivot->es_obligatoria ? 'checked' : '' }}>
                                                    <span class="text-xs">Obligatoria</span>
                                                </label>

                                                <button class="px-3 py-1 rounded bg-blue-600 text-white hover:bg-blue-700">
                                                    Guardar
                                                </button>
                                            </form>
                                        </td>

                                        <td class="py-2 pr-4">
                                            @if($materia->pivot->es_obligatoria)
                                                <span class="px-2 py-1 rounded bg-green-200 text-green-900">Sí</span>
                                            @else
                                                <span class="px-2 py-1 rounded bg-gray-200 text-gray-900">No</span>
                                            @endif
                                        </td>

                                        <td class="py-2 pr-4">
                                            <form method="POST" action="{{ route('plan-estudios.remover-materia', [$plan, $materia]) }}"
                                                  onsubmit="return confirm('¿Quitar esta materia del plan?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="underline text-red-600 hover:text-red-800">
                                                    Quitar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4">No hay materias asignadas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
