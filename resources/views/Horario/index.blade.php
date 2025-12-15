<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Gestión de Horarios
                </h2>
                <p class="mt-1 text-sm text-slate-600">Administra los horarios de las clases</p>
            </div>
            <a href="{{ route('horarios.create') }}"
               class="btn-university inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Horario
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
                <form method="GET" action="{{ route('horarios.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Búsqueda</label>
                        <input name="search" value="{{ request('search') }}"
                               placeholder="Día, tipo o hora..."
                               class="input-university w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Día</label>
                        <select name="dia" class="input-university w-full">
                            <option value="">Todos</option>
                            @foreach($dias as $d)
                                <option value="{{ $d }}" {{ request('dia') === $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Tipo</label>
                        <select name="tipo" class="input-university w-full">
                            <option value="">Todos</option>
                            @foreach($tipos as $t)
                                <option value="{{ $t }}" {{ request('tipo') === $t ? 'selected' : '' }}>
                                    {{ ucfirst($t) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="btn-university flex-1 justify-center">Filtrar</button>
                        <a href="{{ route('horarios.index') }}" class="btn-university-outline flex-1 justify-center">Limpiar</a>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Listado de Horarios</h3>
                    </div>
                    <span class="badge-info">{{ $horarios->total() }} horarios</span>
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
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($horarios as $h)
                            <tr>
                                <td>
                                    <span class="font-semibold text-slate-900">{{ $h->dia }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-900">{{ \Carbon\Carbon::parse($h->hora_inicio)->format('H:i') }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-slate-900">{{ \Carbon\Carbon::parse($h->hora_fin)->format('H:i') }}</span>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($h->tipo) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('horarios.show', $h) }}"
                                           class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-medium hover:bg-blue-100">
                                            Ver
                                        </a>
                                        <a href="{{ route('horarios.edit', $h) }}"
                                           class="inline-flex items-center px-2 py-1 bg-amber-50 text-amber-700 rounded text-xs font-medium hover:bg-amber-100">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('horarios.destroy', $h) }}"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar este horario?')" class="inline">
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
                                <td colspan="5" class="py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-slate-600 font-medium">No hay horarios registrados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($horarios->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $horarios->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>