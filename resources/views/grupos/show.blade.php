<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    {{ $grupo->codigo }}
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $grupo->materia?->nombre }} • {{ $grupo->periodo_academico }}
                </p>
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

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Mensajes --}}
        @if (session('success'))
            <div class="p-4 rounded-lg bg-green-50 border border-green-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-lg bg-red-50 border border-red-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Información del Grupo --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Información del Grupo</h3>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Código</label>
                        <p class="font-mono font-semibold text-university-700">{{ $grupo->codigo }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Período Académico</label>
                        <p class="text-slate-900">{{ $grupo->periodo_academico }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Materia</label>
                        <p class="text-slate-900">
                            <span class="font-mono font-semibold text-university-700">{{ $grupo->materia?->codigo }}</span> - 
                            {{ $grupo->materia?->nombre }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Docente</label>
                        <p class="text-slate-900">
                            {{ $grupo->docente?->nombre ?? 'No asignado' }} {{ $grupo->docente?->apellido }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Estado</label>
                        @if ($grupo->activo)
                            <span class="badge-success">Activo</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Inactivo</span>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Cupos</label>
                        <div class="flex items-center gap-3">
                            <span class="text-lg font-semibold text-slate-900">{{ $grupo->cupo_actual }}/{{ $grupo->cupo_maximo }}</span>
                            <span class="badge-info">{{ $grupo->cupos_disponibles }} disponibles</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Horarios --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Horarios Asignados</h3>
                </div>
            </div>

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
                        @forelse ($grupo->horarios as $h)
                            <tr>
                                <td>
                                    <span class="font-medium text-slate-900">{{ $h->dia }}</span>
                                </td>
                                <td>{{ \Illuminate\Support\Carbon::parse($h->hora_inicio)->format('H:i') }}</td>
                                <td>{{ \Illuminate\Support\Carbon::parse($h->hora_fin)->format('H:i') }}</td>
                                <td>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($h->tipo) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-slate-600 font-medium">Este grupo no tiene horarios asignados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Acciones Rápidas --}}
        <div class="university-card">
            <div class="p-6">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('grupos.edit', $grupo) }}"
                       class="btn-university inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar Grupo
                    </a>

                    <form method="POST" action="{{ route('grupos.destroy', $grupo) }}"
                          onsubmit="return confirm('¿Seguro que deseas eliminar este grupo?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 bg-red-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-red-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar Grupo
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>