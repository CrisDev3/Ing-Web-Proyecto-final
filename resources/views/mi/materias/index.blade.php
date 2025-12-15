<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Materias
                </h2>
                <p class="mt-1 text-sm text-slate-600">Consulta de materias (solo lectura)</p>
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

        {{-- Buscador (opcional). Si aún no filtras en controller, igual sirve para mantener UI --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h3 class="text-lg font-semibold">Buscar Materias</h3>
                </div>
            </div>

            <div class="p-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-900 mb-2">
                            Búsqueda
                        </label>
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Código o nombre..."
                               class="input-university w-full">
                        <p class="text-xs text-slate-500 mt-2">
                            Nota: si aún no implementas filtro en el controller, esto solo conserva la interfaz.
                        </p>
                    </div>

                    <div>
                        <button type="submit" class="btn-university w-full inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla de materias --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="text-lg font-semibold">Listado</h3>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-university">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th class="text-center">Créditos</th>
                            <th class="text-center">Horas (T/P)</th>
                            <th>Planes</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materias as $materia)
                            <tr>
                                <td>
                                    <span class="font-mono font-semibold text-university-700">
                                        {{ $materia->codigo }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm text-slate-900 font-semibold">{{ $materia->nombre }}</div>
                                    @if($materia->descripcion)
                                        <div class="text-xs text-slate-500 mt-1 line-clamp-2">
                                            {{ $materia->descripcion }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center font-semibold text-slate-900">
                                    {{ $materia->creditos }}
                                </td>
                                <td class="text-center text-sm text-slate-700">
                                    {{ $materia->horas_teoricas }} / {{ $materia->horas_practicas }}
                                </td>
                                <td>
                                    @if($materia->planesEstudios->count())
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($materia->planesEstudios->take(3) as $p)
                                                <span class="badge-info">
                                                    {{ $p->codigo ?? $p->nombre }}
                                                </span>
                                            @endforeach
                                            @if($materia->planesEstudios->count() > 3)
                                                <span class="text-xs text-slate-500">
                                                    +{{ $materia->planesEstudios->count() - 3 }} más
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-500">No asignada</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('mi.materias.show', $materia) }}"
                                       class="text-university-700 font-medium hover:underline">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-500">
                                    No hay materias registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $materias->withQueryString()->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
