<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nuevo Grupo
            </h2>

            <a href="{{ route('grupos.index') }}"
               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if ($errors->any())
            <div class="p-3 bg-red-100 text-red-800 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form method="POST" action="{{ route('grupos.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Código</label>
                    <input type="text" name="codigo" value="{{ old('codigo') }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                           required>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Materia</label>
                    <select name="materia_id"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                            required>
                        <option value="">Seleccione...</option>
                        @foreach ($materias as $m)
                            <option value="{{ $m->id }}" {{ (string)old('materia_id') === (string)$m->id ? 'selected' : '' }}>
                                {{ $m->codigo }} - {{ $m->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Docente</label>
                    <select name="docente_id"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                        <option value="">- Sin docente asignado -</option>
                        @foreach ($docentes as $d)
                            <option value="{{ $d->id }}" {{ (string)old('docente_id', $grupo->docente_id ?? '') === (string)$d->id ? 'selected' : '' }}>
                                {{ $d->nombre }} {{ $d->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Período Académico</label>
                    <input type="text" name="periodo_academico" value="{{ old('periodo_academico') }}"
                           placeholder="Ej: 2025-2"
                           class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                           required>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Cupo Máximo</label>
                    <input type="number" name="cupo_maximo" min="1" max="200"
                           value="{{ old('cupo_maximo', 30) }}"
                           class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                           required>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                    <label class="text-sm text-gray-700 dark:text-gray-200">Activo</label>
                </div>

                <div class="pt-2 flex gap-2">
                    <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                        Guardar
                    </button>

                    <a href="{{ route('grupos.index') }}"
                       class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
