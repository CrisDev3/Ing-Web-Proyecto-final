<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Gestión de Estudiantes
                </h2>
                <p class="mt-1 text-sm text-slate-600">Administra la información de los estudiantes del sistema</p>
            </div>
            <a href="{{ route('estudiantes.create') }}"
               class="btn-university inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Estudiante
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
                <form action="{{ route('estudiantes.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-5">
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Búsqueda</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Buscar por nombre, cédula o email..."
                               class="input-university w-full">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Plan de estudios</label>
                        <select name="plan_id" class="input-university w-full">
                            <option value="">Todos los planes</option>
                            @foreach($planes as $plan)
                                <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-900 mb-2">Estado</label>
                        <select name="activo" class="input-university w-full">
                            <option value="">Todos</option>
                            <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                            <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="btn-university flex-1 justify-center">Filtrar</button>
                        <a href="{{ route('estudiantes.index') }}" class="btn-university-outline flex-1 justify-center">Limpiar</a>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Listado de Estudiantes</h3>
                    </div>
                    <span class="badge-info">{{ $estudiantes->total() }} estudiantes</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-university">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Estudiante</th>
                            <th>Contacto</th>
                            <th>Plan de Estudios</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estudiantes as $estudiante)
                            <tr>
                                <td>
                                    <span class="font-mono font-semibold text-university-700">{{ $estudiante->cedula }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-university-100 flex items-center justify-center mr-3">
                                            <span class="text-university-700 font-semibold">
                                                {{ strtoupper(substr($estudiante->nombre, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900">{{ $estudiante->nombre_completo }}</div>
                                            @if($estudiante->usuario)
                                                <div class="text-xs text-slate-500">Usuario vinculado</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <a href="mailto:{{ $estudiante->email }}" class="text-university-700 hover:underline">
                                            {{ $estudiante->email }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $estudiante->telefono ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $estudiante->planEstudios->codigo ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $estudiante->planEstudios->nombre ?? 'Sin plan' }}</div>
                                </td>
                                <td>
                                    @if($estudiante->activo)
                                        <span class="badge-success">Activo</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        <a href="{{ route('estudiantes.show', $estudiante) }}"
                                           class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-medium hover:bg-blue-100">
                                            Ver
                                        </a>
                                        <a href="{{ route('estudiantes.edit', $estudiante) }}"
                                           class="inline-flex items-center px-2 py-1 bg-amber-50 text-amber-700 rounded text-xs font-medium hover:bg-amber-100">
                                            Editar
                                        </a>
                                        <form action="{{ route('estudiantes.toggle-activo', $estudiante) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                           {{ $estudiante->activo ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                                                {{ $estudiante->activo ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('estudiantes.destroy', $estudiante) }}" method="POST"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar este estudiante?')" class="inline">
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
                                <td colspan="6" class="py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <p class="text-slate-600 font-medium">No se encontraron estudiantes</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($estudiantes->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $estudiantes->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>