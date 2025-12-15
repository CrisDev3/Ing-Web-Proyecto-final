<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-3xl text-university-900 leading-tight">
                    Horario del estudiante
                </h2>
                <p class="mt-1 text-sm text-slate-600">
                    {{ $estudiante->nombre }} {{ $estudiante->apellido }} • {{ $estudiante->cedula }}
                </p>
            </div>

            <a href="{{ route('estudiantes.show', $estudiante) }}"
               class="btn-university-outline inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
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

        {{-- Tabla --}}
        <div class="university-card">
            <div class="university-card-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold">Horario</h3>
                    </div>

                    @php
                        $total = is_iterable($horario) ? collect($horario)->flatten(1)->count() : 0;
                    @endphp
                    <span class="badge-info">{{ $total }} bloque(s)</span>
                </div>
            </div>

            @php
                // Si $horario ya viene agrupado por día (['Lunes' => [...], ...]) lo usamos tal cual.
                $h = collect($horario);
                $esAsociativo = $h->keys()->every(fn($k) => !is_int($k));
                $porDia = $esAsociativo ? $h : $h->groupBy(fn($x) => data_get($x, 'dia', 'Sin día'));

                $ordenDias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo','Sin día'];
                $porDia = $porDia->sortBy(fn($_, $dia) => array_search($dia, $ordenDias) !== false ? array_search($dia, $ordenDias) : 999);
            @endphp

            <div class="p-6 space-y-6">
                @forelse($porDia as $dia => $items)
                    <div class="border border-slate-200 rounded-lg overflow-hidden">
                        <div class="bg-slate-50 px-6 py-3 flex items-center justify-between">
                            <div class="font-semibold text-slate-900">{{ $dia }}</div>
                            <div class="text-xs text-slate-600">{{ count($items) }} bloque(s)</div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="table-university">
                                <thead>
                                    <tr>
                                        <th>Hora</th>
                                        <th>Tipo</th>
                                        <th>Grupo</th>
                                        <th>Materia</th>
                                        <th>Docente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $it)
                                        @php
                                            // Intenta sacar datos tanto si es objeto "Horario" como si es arreglo armado por obtenerHorario()
                                            $inicio = data_get($it, 'hora_inicio');
                                            $fin    = data_get($it, 'hora_fin');
                                            $tipo   = data_get($it, 'tipo', '-');

                                            $grupoCodigo   = data_get($it, 'grupo.codigo') ?? data_get($it, 'grupo_codigo') ?? '-';
                                            $materiaNombre = data_get($it, 'grupo.materia.nombre') ?? data_get($it, 'materia') ?? '-';

                                            $docN = data_get($it, 'grupo.docente.nombre');
                                            $docA = data_get($it, 'grupo.docente.apellido');
                                            $docente = trim(($docN ?? '').' '.($docA ?? ''));
                                            if ($docente === '') $docente = data_get($it, 'docente') ?? '-';

                                            $horaTxt = '-';
                                            try {
                                                if ($inicio && $fin) {
                                                    $horaTxt = \Illuminate\Support\Carbon::parse($inicio)->format('H:i')
                                                        .' - '.
                                                        \Illuminate\Support\Carbon::parse($fin)->format('H:i');
                                                }
                                            } catch (\Throwable $e) {}
                                        @endphp

                                        <tr>
                                            <td class="font-mono font-semibold text-university-700">{{ $horaTxt }}</td>
                                            <td>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                    {{ ucfirst($tipo) }}
                                                </span>
                                            </td>
                                            <td class="font-medium text-slate-900">{{ $grupoCodigo }}</td>
                                            <td class="text-slate-900">{{ $materiaNombre }}</td>
                                            <td class="text-slate-900">{{ $docente }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-slate-600 font-medium">Este estudiante no tiene horario registrado</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
