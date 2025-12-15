<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900">
                    Detalle del Grupo
                </h2>
                <p class="text-sm text-slate-600">
                    {{ $grupo->codigo }}
                </p>
            </div>

            <a href="{{ route('mi.grupos.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Información general --}}
        <div class="university-card">
            <div class="university-card-header">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7"/>
                    </svg>
                    Información General
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="table-university">
                    <tbody>
                        <tr>
                            <th class="w-1/3">Materia</th>
                            <td>
                                {{ $grupo->materia->codigo ?? '' }} –
                                {{ $grupo->materia->nombre ?? '—' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Docente</th>
                            <td>
                                {{ $grupo->docente
                                    ? $grupo->docente->nombre . ' ' . $grupo->docente->apellido
                                    : 'Sin asignar'
                                }}
                            </td>
                        </tr>
                        <tr>
                            <th>Cupos</th>
                            <td>
                                <span class="font-semibold">
                                    {{ $grupo->cupo_actual }} / {{ $grupo->cupo_maximo }}
                                </span>
                                <span class="badge-info ml-2">
                                    {{ $grupo->cupos_disponibles }} disponibles
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Estado</th>
                            <td>
                                @if($grupo->activo)
                                    <span class="badge-success">Activo</span>
                                @else
                                    <span class="badge-danger">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Horarios --}}
        <div class="university-card">
            <div class="university-card-header">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10"/>
                    </svg>
                    Horarios
                </h3>
            </div>

            @if($grupo->horarios->count())
                <div class="overflow-x-auto">
                    <table class="table-university">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grupo->horarios as $h)
                                <tr>
                                    <td class="font-semibold">{{ $h->dia }}</td>
                                    <td>{{ \Carbon\Carbon::parse($h->hora_inicio)->format('H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($h->hora_fin)->format('H:i') }}</td>
                                    <td>
                                        <span class="badge-info">
                                            {{ ucfirst($h->tipo) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center text-slate-500 text-sm">
                    Este grupo no tiene horarios asignados.
                </div>
            @endif
        </div>

    </div>
</x-app-layout>


