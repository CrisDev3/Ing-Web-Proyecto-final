<x-app-layout>
    <x-slot name="header">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                Matrícula de Estudiante
            </h2>
            <p class="mt-1 text-sm text-slate-600">Registra a un estudiante en un grupo académico</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-200 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver
        </a>
    </div>
</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Mensajes de éxito/error --}}
        @if(session('success'))
            <div class="p-4 rounded-lg bg-green-50 border border-green-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-lg bg-red-50 border border-red-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Formulario de Matrícula --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Formulario de Matrícula</h3>
                </div>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('matriculas.matricular') }}" class="space-y-8">
                    @csrf

                    {{-- Selección de Estudiante --}}
                    <div>
                        <label for="estudiante_id" class="block text-sm font-semibold text-slate-900 mb-2">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-university-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Estudiante
                                <span class="text-red-500 ml-1">*</span>
                            </span>
                        </label>
                        <select name="estudiante_id" 
                                id="estudiante_id"
                                required
                                class="input-university w-full">
                            <option value="">Seleccione un estudiante</option>
                            @foreach($estudiantes as $e)
                                <option value="{{ $e->id }}">
                                    {{ $e->apellido }}, {{ $e->nombre }} — {{ $e->cedula }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-slate-500">Selecciona el estudiante que deseas matricular</p>
                    </div>

                    {{-- Selección de Grupo --}}
                    <div>
                        <label for="grupo_id" class="block text-sm font-semibold text-slate-900 mb-2">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-university-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Grupo Académico
                                <span class="text-red-500 ml-1">*</span>
                            </span>
                        </label>
                        <select name="grupo_id" 
                                id="grupo_id"
                                required
                                class="input-university w-full">
                            <option value="">Seleccione un grupo</option>
                            @foreach($grupos as $g)
                                <option value="{{ $g->id }}">
                                    {{ $g->codigo }} | {{ $g->materia->nombre ?? 'Materia' }} | Cupos: {{ $g->cupo_actual }}/{{ $g->cupo_maximo }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-slate-500">Selecciona el grupo en el que deseas matricular al estudiante</p>
                    </div>

                    {{-- Información Adicional --}}
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="ml-3">
                                <h4 class="text-sm font-semibold text-blue-900">Información importante</h4>
                                <ul class="mt-2 text-sm text-blue-800 space-y-1 list-disc list-inside">
                                    <li>Verifica que el estudiante cumpla con los prerequisitos de la materia</li>
                                    <li>Asegúrate de que haya cupos disponibles en el grupo</li>
                                    <li>La matrícula se registrará en el periodo académico actual</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-200">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-6 py-2.5 bg-white border-2 border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="btn-university inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Matricular Estudiante
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</x-app-layout>