<x-app-layout>
    {{-- ================= HEADER ================= --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Matrícula de Estudiante
            </h2>

            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100">
                Volver
            </a>
        </div>
    </x-slot>

    {{-- ================= CONTENT ================= --}}
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensajes --}}
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

            {{-- Card --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-6 space-y-6 text-gray-900 dark:text-gray-100">

                    <form method="POST"
                          action="{{ route('matriculas.matricular') }}"
                          class="space-y-6">
                        @csrf

                        {{-- Estudiante --}}
                        <div>
                            <label class="block text-sm mb-1 font-medium">
                                Estudiante
                            </label>
                            <select name="estudiante_id"
                                    required
                                    class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                <option value="">Seleccione un estudiante</option>
                                @foreach($estudiantes as $e)
                                    <option value="{{ $e->id }}">
                                        {{ $e->apellido }}, {{ $e->nombre }} — {{ $e->cedula }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Grupo --}}
                        <div>
                            <label class="block text-sm mb-1 font-medium">
                                Grupo
                            </label>
                            <select name="grupo_id"
                                    required
                                    class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                <option value="">Seleccione un grupo</option>
                                @foreach($grupos as $g)
                                    <option value="{{ $g->id }}">
                                        {{ $g->codigo }}
                                        | {{ $g->materia->nombre ?? 'Materia' }}
                                        | Cupo: {{ $g->cupo_actual }}/{{ $g->cupo_maximo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Botones --}}
                        <div class="flex justify-end gap-3 pt-4">
                            <a href="{{ route('dashboard') }}"
                               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-100">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="px-5 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                                Matricular
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>

