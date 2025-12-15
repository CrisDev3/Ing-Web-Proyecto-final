<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    {{ $materia->nombre }}
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    <span class="font-mono font-semibold text-university-700">{{ $materia->codigo }}</span> • 
                    {{ $materia->creditos }} créditos
                </p>
            </div>
            <a href="{{ route('materias.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Información General --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Información General</h3>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Código</label>
                        <p class="font-mono font-semibold text-university-700">{{ $materia->codigo }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre</label>
                        <p class="text-slate-900">{{ $materia->nombre }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Créditos</label>
                        <p class="text-slate-900">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $materia->creditos }} créditos
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Horas Totales</label>
                        <p class="text-slate-900">{{ $materia->total_horas }} horas</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Horas Teóricas</label>
                        <p class="text-slate-900">{{ $materia->horas_teoricas ?? 0 }} horas</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Horas Prácticas</label>
                        <p class="text-slate-900">{{ $materia->horas_practicas ?? 0 }} horas</p>
                    </div>

                    @if($materia->descripcion)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Descripción</label>
                            <p class="text-slate-900">{{ $materia->descripcion }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Planes de Estudio --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Planes de Estudio Asociados</h3>
                    </div>
                    <span class="badge-info">{{ $materia->planesEstudios->count() }} planes</span>
                </div>
            </div>

            <div class="p-6">
                @forelse($materia->planesEstudios as $plan)
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg mb-3 last:mb-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-semibold text-university-700">{{ $plan->codigo }}</span>
                                    <span class="text-slate-400">•</span>
                                    <span class="font-medium text-slate-900">{{ $plan->nombre }}</span>
                                </div>
                                <div class="mt-2 flex items-center gap-3 text-sm text-slate-600">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Semestre {{ $plan->pivot->semestre }}
                                    </span>
                                    @if($plan->pivot->es_obligatoria)
                                        <span class="badge-success">Obligatoria</span>
                                    @else
                                        <span class="badge-info">Electiva</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-slate-600 font-medium">No está asignada a ningún plan de estudios</p>
                        <p class="text-sm text-slate-500 mt-1">Edita la materia para asignarla a un plan</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Acciones Rápidas --}}
        <div class="university-card">
            <div class="p-6">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('materias.edit', $materia) }}"
                       class="btn-university inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Materia
                    </a>

                    <a href="{{ route('materias.index') }}"
                       class="btn-university-outline inline-flex items-center">
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