<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Gestión de Materias
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    <span class="font-mono font-semibold text-university-700">{{ $plan->codigo }}</span> - {{ $plan->nombre }}
                </p>
            </div>
            <a href="{{ route('plan-estudios.index') }}"
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

        {{-- Formulario: Asignar Materia --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-white">Asignar Nueva Materia</h3>
                </div>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('plan-estudios.agregar-materia', $plan) }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    @csrf

                    <div class="md:col-span-5">
                        <label for="materia_id" class="block text-sm font-semibold text-slate-900 mb-2">
                            Materia
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="materia_id" 
                                id="materia_id"
                                class="input-university w-full"
                                required>
                            <option value="">Seleccione una materia</option>
                            @foreach($materiasDisponibles as $m)
                                <option value="{{ $m->id }}">{{ $m->codigo }} - {{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="semestre" class="block text-sm font-semibold text-slate-900 mb-2">
                            Semestre
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="semestre"
                               name="semestre" 
                               min="1" 
                               max="20"
                               value="1"
                               class="input-university w-full"
                               required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="flex items-center cursor-pointer p-3 bg-slate-50 rounded-lg border border-slate-200 hover:bg-slate-100 transition-colors">
                            <input type="checkbox" 
                                   name="es_obligatoria" 
                                   value="1"
                                   class="rounded border-slate-300 text-university-700 shadow-sm focus:ring-university-500">
                            <span class="ml-2 text-sm font-medium text-slate-900">Obligatoria</span>
                        </label>
                    </div>

                    <div class="md:col-span-3">
                        <button type="submit" class="btn-university w-full inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Asignar Materia
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla: Materias Asignadas --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Materias Asignadas</h3>
                    </div>
                    <span class="badge-info">{{ $plan->materiasAsignadas->count() }} materias</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table-university">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre de la Materia</th>
                            <th>Semestre</th>
                            <th>Tipo</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plan->materiasAsignadas as $m)
                            <tr>
                                <td>
                                    <span class="font-mono font-semibold text-university-700">{{ $m->codigo }}</span>
                                </td>
                                <td>
                                    <div class="font-medium text-slate-900">{{ $m->nombre }}</div>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Semestre {{ $m->pivot->semestre }}
                                    </span>
                                </td>
                                <td>
                                    @if($m->pivot->es_obligatoria)
                                        <span class="badge-success">Obligatoria</span>
                                    @else
                                        <span class="badge-info">Electiva</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-center">
                                        <form method="POST" 
                                              action="{{ route('plan-estudios.remover-materia', [$plan, $m]) }}"
                                              onsubmit="return confirm('¿Estás seguro de remover esta materia del plan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 rounded-md text-xs font-medium hover:bg-red-100 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Remover
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <p class="text-slate-600 font-medium">No hay materias asignadas</p>
                                    <p class="text-sm text-slate-500 mt-1">Usa el formulario superior para asignar materias a este plan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>