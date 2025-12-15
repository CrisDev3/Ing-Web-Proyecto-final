<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-serif font-bold text-3xl text-university-900">
                Grupos Disponibles
            </h2>
            <p class="text-sm text-slate-600">
                Consulta de grupos, horarios y cupos
            </p>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Buscador --}}
        <form method="GET" class="flex gap-2">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Buscar por código o materia..."
                class="input-university w-full"
            >
            <button class="btn-university">
                Buscar
            </button>
        </form>

        {{-- Tabla --}}
        <div class="university-card overflow-x-auto">
            <table class="table-university">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Materia</th>
                        <th>Docente</th>
                        <th>Cupos</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grupos as $grupo)
                        <tr>
                            <td class="font-mono font-semibold">
                                {{ $grupo->codigo }}
                            </td>
                            <td>
                                {{ $grupo->materia->codigo ?? '' }} -
                                {{ $grupo->materia->nombre ?? '—' }}
                            </td>
                            <td>
                                {{ $grupo->docente
                                    ? $grupo->docente->nombre . ' ' . $grupo->docente->apellido
                                    : 'Sin asignar'
                                }}
                            </td>
                            <td>
                                <span class="font-semibold">
                                    {{ $grupo->cupo_actual }}/{{ $grupo->cupo_maximo }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('mi.grupos.show', $grupo) }}"
                                   class="text-university-700 font-medium hover:underline">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500">
                                No hay grupos disponibles
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        {{ $grupos->withQueryString()->links() }}

    </div>
</x-app-layout>
