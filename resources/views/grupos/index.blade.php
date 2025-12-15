<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Gestión de Grupos
                </h2>
                <p class="mt-1 text-sm text-slate-600">Administra los grupos académicos y su oferta</p>
            </div>
            <a href="{{ route('grupos.create') }}"
               class="btn-university inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Grupo
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

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

        {{-- Filtros --}}
        <div class="university-card">
            <div class="p-6">
                <form method="GET" action="{{ route('grupos.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Código o período..."
                               class="input-university w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Materia</label>
                        <select name="materia_id" class="input-university w-full">
                            <option value="">Todas</option>
                            @foreach ($materias as $m)
                                <option value="{{ $m->id }}" {{ (string)request('materia_id') === (string)$m->id ? 'selected' : '' }}>
                                    {{ $m->codigo }} - {{ $m->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Docente</label>
                        <select name="docente_id" class="input-university w-full">
                            <option value="">Todos</option>
                            @foreach ($docentes as $d)
                                <option value="{{ $d->id }}" {{ (string)request('docente_id') === (string)$d->id ? 'selected' : '' }}>
                                    {{ $d->nombre }} {{ $d->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Estado</label>
                        <select name="activo" class="input-university w-full">
                            <option value="">Todos</option>
                            <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="btn-university flex-1 justify-center">Filtrar</button>
                        <a href="{{ route('grupos.index') }}" class="btn-university-outline flex-1 justify-center">Limpiar</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Listado de Grupos</h3>
                    </div>
                    <span class="badge-info">{{ $grupos->total() }} grupos</span>
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
                            <th>Cupos</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($grupos as $g)
                            <tr>
                                <td>
                                    <span class="font-mono font-semibold text-university-700">{{ $g->codigo }}</span>
                                </td>
                                <td>
                                    <div class="font-medium text-slate-900">{{ $g->materia?->nombre }}</div>
                                    <div class="text-xs text-slate-500">{{ $g->materia?->codigo }}</div>
                                </td>
                                <td>
                                    <div class="text-sm text-slate-900">{{ $g->docente?->nombre }} {{ $g->docente?->apellido }}</div>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-900">{{ $g->periodo_academico }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-900">{{ $g->cupo_actual }}/{{ $g->cupo_maximo }}</span>
                                        <span class="text-xs text-slate-500">({{ $g->cupos_disponibles }} disp.)</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($g->activo)
                                        <span class="badge-success">Activo</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('grupos.show', $g) }}"
                                           class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-medium hover:bg-blue-100">
                                            Ver
                                        </a>
                                        <a href="{{ route('grupos.edit', $g) }}"
                                           class="inline-flex items-center px-2 py-1 bg-amber-50 text-amber-700 rounded text-xs font-medium hover:bg-amber-100">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('grupos.destroy', $g) }}"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar este grupo?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-2 py-1 bg-red-50 text-red-700 rounded text-xs font-medium hover:bg-red-100">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-slate-600 font-medium">No hay grupos registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($grupos->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $grupos->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>