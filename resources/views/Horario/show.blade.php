<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Detalle del Horario
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $horario->dia }} • {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }} • {{ ucfirst($horario->tipo) }}
                </p>
            </div>
            <a href="{{ route('horarios.index') }}"
               class="inline-flex items-center px-4 py-2 bg-slate-100 border border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Información del Horario --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Información del Horario</h3>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Día</label>
                        <p class="text-lg font-semibold text-university-700">{{ $horario->dia }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Hora Inicio</label>
                        <p class="text-slate-900">{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Hora Fin</label>
                        <p class="text-slate-900">{{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tipo</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($horario->tipo) }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200 flex gap-3">
                    <a href="{{ route('horarios.edit', $horario) }}"
                       class="btn-university inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Horario
                    </a>

                    <form method="POST" action="{{ route('horarios.destroy', $horario) }}"
                          onsubmit="return confirm('¿Seguro que deseas eliminar este horario?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-red-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar Horario
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Grupos que usan este horario --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Grupos Asignados</h3>
                    </div>
                    <span class="badge-info">{{ $horario->grupos->count() }} grupos</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-university">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Materia</th>
                            <th>Docente</th>
                            <th>Período</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($horario->grupos as $g)
                            <tr>
                                <td>
                                    <span class="font-mono font-semibold text-university-700">{{ $g->codigo }}</span>
                                </td>
                                <td>
                                    <div class="font-medium text-slate-900">{{ $g->materia?->nombre }}</div>
                                    <div class="text-xs text-slate-500">{{ $g->materia?->codigo }}</div>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-900">{{ $g->docente?->nombre_completo ?? 'Sin asignar' }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-900">{{ $g->periodo_academico }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-slate-600 font-medium">No hay grupos usando este horario</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>