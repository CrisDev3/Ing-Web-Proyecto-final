<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Planes de Estudio
                </h2>
                <p class="mt-1 text-sm text-slate-600">Consulta de programas académicos (solo lectura)</p>
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Listado de Planes</h3>
                    </div>
                    <span class="badge-info">{{ $planes->total() }} planes</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-university">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre del Programa</th>
                            <th>Duración</th>
                            <th>Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($planes as $plan)
                            <tr>
                                <td>
                                    <span class="font-mono font-semibold text-university-700">{{ $plan->codigo }}</span>
                                </td>
                                <td>
                                    <div class="font-medium text-slate-900">{{ $plan->nombre }}</div>
                                </td>
                                <td>
                                    <span class="text-slate-600">{{ $plan->duracion_semestres }} semestres</span>
                                </td>
                                <td>
                                    @if($plan->activo)
                                        <span class="badge-success">Activo</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('mi.planes.show', $plan) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-md text-xs font-medium hover:bg-blue-100 transition-colors"
                                       title="Ver detalles">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-slate-600 font-medium">No hay planes de estudio registrados</p>
                                    <p class="text-sm text-slate-500 mt-1">Consulta al administrador para agregar planes</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($planes->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $planes->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
