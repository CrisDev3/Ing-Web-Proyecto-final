<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Crear Grupo
                </h2>
                <p class="mt-1 text-sm text-slate-600">Registra un nuevo grupo académico</p>
            </div>
            <a href="{{ route('grupos.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800">Hay errores en el formulario:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Formulario --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Información del Grupo</h3>
                </div>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('grupos.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="codigo" class="block text-sm font-semibold text-slate-900 mb-2">
                            Código del Grupo
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="codigo"
                               name="codigo"
                               value="{{ old('codigo') }}"
                               class="input-university w-full"
                               placeholder="Ej: MAT101-A"
                               required>
                    </div>

                    <div>
                        <label for="materia_id" class="block text-sm font-semibold text-slate-900 mb-2">
                            Materia
                            <span class="text-red-500">*</span>
                        </label>
                        <select id="materia_id"
                                name="materia_id"
                                class="input-university w-full"
                                required>
                            <option value="">Seleccione una materia</option>
                            @foreach ($materias as $m)
                                <option value="{{ $m->id }}" {{ (string)old('materia_id') === (string)$m->id ? 'selected' : '' }}>
                                    {{ $m->codigo }} - {{ $m->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="docente_id" class="block text-sm font-semibold text-slate-900 mb-2">
                            Docente
                        </label>
                        <select id="docente_id"
                                name="docente_id"
                                class="input-university w-full">
                            <option value="">Sin docente asignado</option>
                            @foreach ($docentes as $d)
                                <option value="{{ $d->id }}" {{ (string)old('docente_id') === (string)$d->id ? 'selected' : '' }}>
                                    {{ $d->nombre }} {{ $d->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="periodo_academico" class="block text-sm font-semibold text-slate-900 mb-2">
                            Período Académico
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="periodo_academico"
                               name="periodo_academico"
                               value="{{ old('periodo_academico') }}"
                               class="input-university w-full"
                               placeholder="Ej: 2025-1"
                               required>
                        <p class="mt-1 text-xs text-slate-500">Formato: Año-Semestre (Ej: 2025-1)</p>
                    </div>

                    <div>
                        <label for="cupo_maximo" class="block text-sm font-semibold text-slate-900 mb-2">
                            Cupo Máximo
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               id="cupo_maximo"
                               name="cupo_maximo"
                               min="1"
                               max="200"
                               value="{{ old('cupo_maximo', 30) }}"
                               class="input-university w-full"
                               required>
                        <p class="mt-1 text-xs text-slate-500">Número máximo de estudiantes permitidos</p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="activo"
                                   value="1"
                                   class="rounded border-slate-300 text-university-700 shadow-sm focus:ring-university-500"
                                   {{ old('activo', true) ? 'checked' : '' }}>
                            <span class="ml-3">
                                <span class="text-sm font-semibold text-slate-900">Grupo Activo</span>
                                <span class="block text-xs text-slate-600 mt-1">Los grupos activos están disponibles para matrícula</span>
                            </span>
                        </label>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-200">
                        <a href="{{ route('grupos.index') }}"
                           class="inline-flex items-center px-6 py-2.5 bg-white border-2 border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="btn-university inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Crear Grupo
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-app-layout>