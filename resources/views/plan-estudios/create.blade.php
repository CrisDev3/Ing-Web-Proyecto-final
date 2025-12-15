<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Crear Plan de Estudios
                </h2>
                <p class="mt-1 text-sm text-slate-600">Define un nuevo programa académico</p>
            </div>
            <a href="{{ route('plan-estudios.index') }}"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Información del Plan</h3>
                </div>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('plan-estudios.store') }}" class="space-y-6">
                    @csrf

                    {{-- Nombre --}}
                    <div>
                        <label for="nombre" class="block text-sm font-semibold text-slate-900 mb-2">
                            Nombre del Programa
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nombre"
                               name="nombre" 
                               value="{{ old('nombre') }}"
                               class="input-university w-full"
                               placeholder="Ej: Ingeniería en Sistemas de Información"
                               required>
                        <p class="mt-1 text-xs text-slate-500">Nombre completo del programa académico</p>
                    </div>

                    {{-- Código --}}
                    <div>
                        <label for="codigo" class="block text-sm font-semibold text-slate-900 mb-2">
                            Código
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="codigo"
                               name="codigo" 
                               value="{{ old('codigo') }}"
                               class="input-university w-full"
                               placeholder="Ej: ISI-2024"
                               required>
                        <p class="mt-1 text-xs text-slate-500">Código único identificador del plan</p>
                    </div>

                    {{-- Duración --}}
                    <div>
                        <label for="duracion_semestres" class="block text-sm font-semibold text-slate-900 mb-2">
                            Duración (semestres)
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="duracion_semestres"
                               name="duracion_semestres" 
                               value="{{ old('duracion_semestres', 10) }}"
                               min="1"
                               max="20"
                               class="input-university w-full"
                               required>
                        <p class="mt-1 text-xs text-slate-500">Número total de semestres del programa</p>
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="descripcion" class="block text-sm font-semibold text-slate-900 mb-2">
                            Descripción
                        </label>
                        <textarea id="descripcion"
                                  name="descripcion" 
                                  rows="4"
                                  class="input-university w-full"
                                  placeholder="Descripción detallada del programa académico...">{{ old('descripcion') }}</textarea>
                        <p class="mt-1 text-xs text-slate-500">Opcional: Información adicional sobre el plan de estudios</p>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-200">
                        <a href="{{ route('plan-estudios.index') }}"
                           class="inline-flex items-center px-6 py-2.5 bg-white border-2 border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="btn-university inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Crear Plan de Estudios
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-app-layout>