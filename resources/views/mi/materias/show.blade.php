<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Detalle de Materia
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $materia->codigo }} — {{ $materia->nombre }}
                </p>
            </div>

            <a href="{{ route('mi.materias.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Info general --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="text-lg font-semibold">Información</h3>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <p class="text-slate-500">Código</p>
                    <p class="font-mono font-semibold text-university-700">{{ $materia->codigo }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Créditos</p>
                    <p class="font-semibold text-slate-900">{{ $materia->creditos }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Horas Teóricas</p>
                    <p class="font-semibold text-slate-900">{{ $materia->horas_teoricas }}</p>
                </div>

                <div>
                    <p class="text-slate-500">Horas Prácticas</p>
                    <p class="font-semibold text-slate-900">{{ $materia->horas_practicas }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-slate-500">Descripción</p>
                    <p class="text-slate-800 mt-1">
                        {{ $materia->descripcion ?? 'Sin descripción.' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Planes donde aparece --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                    <h3 class="text-lg font-semibold">Planes de Estudio</h3>
                </div>
            </div>

            <div class="p-6">
                @if($materia->planesEstudios->count())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($materia->planesEstudios as $plan)
                            <div class="border border-slate-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $plan->nombre }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $plan->codigo ?? '—' }}
                                        </p>
                                    </div>

                                    {{-- Si tienes rutas mi.planes.show, lo enlazamos --}}
                                    @if(Route::has('mi.planes.show'))
                                        <a href="{{ route('mi.planes.show', $plan) }}"
                                           class="text-university-700 font-medium text-sm hover:underline">
                                            Ver plan
                                        </a>
                                    @endif
                                </div>

                                {{-- Pivot opcional: semestre / obligatoria --}}
                                @if(isset($plan->pivot))
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @if(isset($plan->pivot->semestre))
                                            <span class="badge-info">Semestre {{ $plan->pivot->semestre }}</span>
                                        @endif
                                        @if(isset($plan->pivot->es_obligatoria))
                                            <span class="badge-info">
                                                {{ $plan->pivot->es_obligatoria ? 'Obligatoria' : 'Electiva' }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500">Esta materia no está asignada a ningún plan.</p>
                @endif
            </div>
        </div>

        {{-- Prerequisitos (si los cargaste en miShow) --}}
        @if(isset($materia->prerequisitos) || isset($materia->dependientes))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="university-card">
                    <div class="university-card-header">
                        <h3 class="text-lg font-semibold">Prerrequisitos</h3>
                    </div>
                    <div class="p-6">
                        @if(isset($materia->prerequisitos) && $materia->prerequisitos->count())
                            <ul class="space-y-2 text-sm">
                                @foreach($materia->prerequisitos as $pre)
                                    <li class="border border-slate-200 rounded-lg p-3">
                                        <span class="font-mono font-semibold text-university-700">{{ $pre->codigo }}</span>
                                        <span class="ml-2 text-slate-900">{{ $pre->nombre }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-slate-500">No tiene prerrequisitos.</p>
                        @endif
                    </div>
                </div>

                <div class="university-card">
                    <div class="university-card-header">
                        <h3 class="text-lg font-semibold">Dependientes</h3>
                    </div>
                    <div class="p-6">
                        @if(isset($materia->dependientes) && $materia->dependientes->count())
                            <ul class="space-y-2 text-sm">
                                @foreach($materia->dependientes as $dep)
                                    <li class="border border-slate-200 rounded-lg p-3">
                                        <span class="font-mono font-semibold text-university-700">{{ $dep->codigo }}</span>
                                        <span class="ml-2 text-slate-900">{{ $dep->nombre }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-slate-500">No hay materias dependientes.</p>
                        @endif
                    </div>
                </div>

            </div>
        @endif

    </div>
</x-app-layout>
