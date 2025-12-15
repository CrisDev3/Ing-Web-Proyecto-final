<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    {{ $estudiante->nombre_completo }}
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    <span class="font-mono font-semibold text-university-700">{{ $estudiante->cedula }}</span> • 
                    {{ $estudiante->email }}
                </p>
            </div>
            <a href="{{ route('estudiantes.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Información Personal --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Información Personal</h3>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre Completo</label>
                        <p class="text-slate-900">{{ $estudiante->nombre_completo }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Cédula</label>
                        <p class="font-mono font-semibold text-university-700">{{ $estudiante->cedula }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Correo Electrónico</label>
                        <p class="text-slate-900">
                            <a href="mailto:{{ $estudiante->email }}" class="text-university-700 hover:underline">
                                {{ $estudiante->email }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Teléfono</label>
                        <p class="text-slate-900">{{ $estudiante->telefono ?? 'No registrado' }}</p>
                    </div>

                    @if($estudiante->direccion)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Dirección</label>
                            <p class="text-slate-900">{{ $estudiante->direccion }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Estado</label>
                        @if($estudiante->activo)
                            <span class="badge-success">Activo</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Inactivo</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Información Académica --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Información Académica</h3>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Plan de Estudios</label>
                        <div class="p-4 bg-university-50 border border-university-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-university-700 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="font-semibold text-university-900">{{ $estudiante->planEstudios->nombre }}</p>
                                    <p class="text-sm text-university-700 mt-1">Código: {{ $estudiante->planEstudios->codigo }}</p>
                                    <p class="text-sm text-slate-600 mt-1">Duración: {{ $estudiante->planEstudios->duracion_semestres }} semestres</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones Rápidas --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Acciones Rápidas</h3>
                </div>
            </div>

            <div class="p-6">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('estudiantes.edit', $estudiante) }}"
                       class="btn-university inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Información
                    </a>

                    @if(Route::has('estudiantes.horario'))
                        <a href="{{ route('estudiantes.horario', $estudiante) }}"
                           class="btn-university-outline inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ver Horario
                        </a>
                    @endif

                    <a href="{{ route('estudiantes.index') }}"
                       class="inline-flex items-center px-6 py-2.5 bg-slate-100 border border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Volver al Listado
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>