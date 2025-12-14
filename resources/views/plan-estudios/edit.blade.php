<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Plan: {{ $plan->nombre }}
            </h2>

            <a href="{{ route('plan-estudios.index') }}"
               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('plan-estudios.update', ['plan_estudio' => $plan->id]) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block mb-1">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $plan->nombre) }}"
                                   class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                                   required>
                        </div>

                        <div>
                            <label class="block mb-1">Código</label>
                            <input type="text" name="codigo" value="{{ old('codigo', $plan->codigo) }}"
                                   class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                                   required>
                        </div>

                        <div>
                            <label class="block mb-1">Duración (semestres)</label>
                            <input type="number" name="duracion_semestres"
                                   value="{{ old('duracion_semestres', $plan->duracion_semestres) }}"
                                   min="1"
                                   class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                                   required>
                        </div>

                        <div>
                            <label class="block mb-1">Descripción</label>
                            <textarea name="descripcion" rows="4"
                                      class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">{{ old('descripcion', $plan->descripcion) }}</textarea>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="activo" value="1"
                                   {{ old('activo', $plan->activo) ? 'checked' : '' }}>
                            <label>Activo</label>
                        </div>

                        <div class="pt-2 flex gap-2">
                            <button type="submit"
                                    class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Actualizar
                            </button>

                            <a href="{{ route('plan-estudios.index') }}"
                               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                                Cancelar
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>

