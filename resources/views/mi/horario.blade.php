<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Mi Horario
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $estudiante->nombre }} {{ $estudiante->apellido }}
                </p>
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Mensajes --}}
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

        {{-- Resumen --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold">Resumen</h3>
                    </div>
                    <span class="badge-info">{{ $matriculas->count() }} grupos</span>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="border border-slate-200 rounded-lg p-4">
                    <p class="text-slate-500">Estudiante</p>
                    <p class="font-semibold text-slate-900">{{ $estudiante->nombre }} {{ $estudiante->apellido }}</p>
                </div>
                <div class="border border-slate-200 rounded-lg p-4">
                    <p class="text-slate-500">Materias inscritas</p>
                    <p class="font-semibold text-slate-900">
                        {{ $matriculas->pluck('grupo.materia_id')->unique()->count() }}
                    </p>
                </div>
                <div class="border border-slate-200 rounded-lg p-4">
                    <p class="text-slate-500">Grupos</p>
                    <p class="font-semibold text-slate-900">{{ $matriculas->count() }}</p>
                </div>
            </div>
        </div>

        {{-- Horario en tabla --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold">Horario Detallado</h3>
                </div>
            </div>

            @if($matriculas->isEmpty())
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-slate-600 font-medium">Aún no tienes matrícula</p>
                    <p class="text-sm text-slate-500 mt-1">Ve a Auto-Matrícula para inscribirte en un grupo.</p>

                    @if(Route::has('mi.matricular'))
                        <div class="mt-6">
                            <a href="{{ route('mi.matricular') }}" class="btn-university inline-flex items-center">
                                Ir a Auto-Matrícula
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="table-university">
                        <thead>
                            <tr>
                                <th>Materia</th>
                                <th>Grupo</th>
                                <th>Docente</th>
                                <th>Horario</th>
                                <th class="text-center">Créditos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matriculas as $mat)
                                @php
                                    $grupo = $mat->grupo;
                                    $materia = $grupo?->materia;
                                    $docente = $grupo?->docente;
                                    $horarios = $grupo?->horarios ?? collect();
                                @endphp

                                <tr>
                                    <td>
                                        @if($materia)
                                            <div class="text-sm text-slate-900 font-semibold">
                                                {{ $materia->codigo }} - {{ $materia->nombre }}
                                            </div>
                                        @else
                                            <span class="text-sm text-slate-500">Materia no disponible</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="font-mono font-semibold text-university-700">
                                            {{ $grupo->codigo ?? '—' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="text-sm text-slate-900">
                                            {{ $docente ? $docente->nombre . ' ' . $docente->apellido : 'Sin asignar' }}
                                        </div>
                                    </td>

                                    <td>
                                        @if($horarios->count())
                                            <div class="space-y-1">
                                                @foreach($horarios as $h)
                                                    <div class="text-xs text-slate-700">
                                                        <span class="font-semibold">{{ $h->dia }}</span>
                                                        {{ \Carbon\Carbon::parse($h->hora_inicio)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($h->hora_fin)->format('H:i') }}
                                                        <span class="text-slate-500">({{ ucfirst($h->tipo) }})</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-500">Sin horario</span>
                                        @endif
                                    </td>

                                    <td class="text-center font-semibold text-slate-900">
                                        {{ $materia->creditos ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
