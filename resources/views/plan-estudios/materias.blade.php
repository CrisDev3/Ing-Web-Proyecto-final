<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Materias del Plan: {{ $plan->nombre }} ({{ $plan->codigo }})
            </h2>

            <a href="{{ route('plan-estudios.index') }}"
               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600">
                Volver
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

        {{-- Form: asignar materia --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <h3 class="font-semibold mb-4 text-gray-900 dark:text-gray-100">Asignar materia</h3>

            <form method="POST" action="{{ route('plan-estudios.agregar-materia', $plan) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Materia</label>
                    <select name="materia_id" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                        @foreach($materiasDisponibles as $m)
                            <option value="{{ $m->id }}">{{ $m->codigo }} - {{ $m->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Semestre</label>
                    <input type="number" name="semestre" min="1" max="3" value="1"
       class="w-full rounded border-gray-300">
                </div>

                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" name="es_obligatoria" value="1">
                    <span class="text-sm text-gray-700 dark:text-gray-200">Obligatoria</span>
                </div>

                <div class="mt-6">
                    <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                        Asignar
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabla: materias asignadas --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <h3 class="font-semibold mb-4 text-gray-900 dark:text-gray-100">Materias asignadas</h3>

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b dark:border-gray-700">
                        <th class="py-2">Código</th>
                        <th>Materia</th>
                        <th>Semestre</th>
                        <th>Obligatoria</th>
                        <th class="text-right">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plan->materiasAsignadas as $m)
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-2">{{ $m->codigo }}</td>
                            <td>{{ $m->nombre }}</td>
                            <td>{{ $m->pivot->semestre }}</td>
                            <td>{{ $m->pivot->es_obligatoria ? 'Sí' : 'No' }}</td>
                            <td class="text-right">
                                <form method="POST" action="{{ route('plan-estudios.remover-materia', [$plan, $m]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                        Remover
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-3 text-gray-500">No hay materias asignadas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
