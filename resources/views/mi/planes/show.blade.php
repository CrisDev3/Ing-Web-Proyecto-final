<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Detalle del Plan
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $plan->codigo }} — {{ $plan->nombre }}
                </p>
            </div>

            <a href="{{ route('mi.planes.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Info del plan --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Información del Plan</h3>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <p class="text-slate-500">Código</p>
                    <p class="font-mono font-semibold text-university-700">{{ $plan->codigo }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Duración</p>
                    <p class="font-semibold text-slate-900">{{ $plan->duracion_semestres }} semestres</p>
                </div>

                <div>
                    <p class="text-slate-500">Estado</p>
                    @if($plan->activo)
                        <span class="badge-success">Activo</span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Inactivo</span>
                    @endif
                </div>

                <div class="md:col-span-2">
                    <p class="text-slate-500">Descripción</p>
                    <p class="text-slate-800 mt-1">
                        {{ $plan->descripcion ?? 'Sin descripción.' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Materias del plan por semestre --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Materias del Plan</h3>
                    </div>
                    <span class="badge-info">{{ $plan->materias->count() }} materias</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-university">
                    <thead>
                        <tr>
                            <th>Semestre</th>
                            <th>Código</th>
                            <th>Materia</th>
                            <th class="text-center">Créditos</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($plan->materias as $m)
                            <tr>
                                <td>
                                    <span class="badge-info">
                                        Sem {{ $m->pivot->semestre ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-mono font-semibold text-university-700">
                                        {{ $m->codigo }}
                                    </span>
                                </td>
                                <td>
                                    <div class="font-medium text-slate-900">{{ $m->nombre }}</div>
                                </td>
                                <td class="text-center font-semibold text-slate-900">
                                    {{ $m->creditos }}
                                </td>
                                <td class="text-center">
                                    @if(isset($m->pivot) && isset($m->pivot->es_obligatoria))
                                        @if($m->pivot->es_obligatoria)
                                            <span class="badge-success">Obligatoria</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Electiva</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-500">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(Route::has('mi.materias.show'))
                                        <a href="{{ route('mi.materias.show', $m) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md text-xs font-medium hover:bg-blue-100 transition-colors"
                                           title="Ver materia">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-500">
                                    Este plan no tiene materias asignadas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
