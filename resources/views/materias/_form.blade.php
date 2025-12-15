<div class="university-card">
    <div class="university-card-header">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="text-lg font-semibold text-white">Información de la Materia</h3>
        </div>
    </div>

    <div class="p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="codigo" class="block text-sm font-semibold text-slate-900 mb-2">
                    Código
                    <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="codigo"
                       name="codigo"
                       value="{{ old('codigo', $materia->codigo ?? '') }}"
                       class="input-university w-full"
                       placeholder="Ej: MAT101"
                       required>
            </div>

            <div>
                <label for="nombre" class="block text-sm font-semibold text-slate-900 mb-2">
                    Nombre
                    <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nombre"
                       name="nombre"
                       value="{{ old('nombre', $materia->nombre ?? '') }}"
                       class="input-university w-full"
                       placeholder="Ej: Cálculo I"
                       required>
            </div>

            <div>
                <label for="creditos" class="block text-sm font-semibold text-slate-900 mb-2">
                    Créditos
                    <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       id="creditos"
                       name="creditos"
                       value="{{ old('creditos', $materia->creditos ?? '') }}"
                       class="input-university w-full"
                       min="1"
                       required>
            </div>

            <div>
                <label for="horas_teoricas" class="block text-sm font-semibold text-slate-900 mb-2">
                    Horas Teóricas
                </label>
                <input type="number"
                       id="horas_teoricas"
                       name="horas_teoricas"
                       value="{{ old('horas_teoricas', $materia->horas_teoricas ?? 0) }}"
                       class="input-university w-full"
                       min="0">
            </div>

            <div>
                <label for="horas_practicas" class="block text-sm font-semibold text-slate-900 mb-2">
                    Horas Prácticas
                </label>
                <input type="number"
                       id="horas_practicas"
                       name="horas_practicas"
                       value="{{ old('horas_practicas', $materia->horas_practicas ?? 0) }}"
                       class="input-university w-full"
                       min="0">
            </div>

            <div class="md:col-span-2">
                <label for="descripcion" class="block text-sm font-semibold text-slate-900 mb-2">
                    Descripción
                </label>
                <textarea id="descripcion"
                          name="descripcion"
                          rows="3"
                          class="input-university w-full"
                          placeholder="Descripción de la materia...">{{ old('descripcion', $materia->descripcion ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Planes de Estudio --}}
<div class="university-card mt-6">
    <div class="university-card-header">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg font-semibold text-white">Asignar a Planes de Estudio</h3>
        </div>
    </div>

    <div class="p-6">
        <div class="space-y-3">
            @foreach($planes as $plan)
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                        <div class="md:col-span-1">
                            <input type="checkbox"
                                   id="plan_{{ $plan->id }}"
                                   name="planes[{{ $plan->id }}][id]"
                                   value="{{ $plan->id }}"
                                   class="rounded border-slate-300 text-university-700 shadow-sm focus:ring-university-500"
                                   {{ isset($materia) && $materia->planesEstudios->contains($plan->id) ? 'checked' : '' }}>
                        </div>

                        <div class="md:col-span-5">
                            <label for="plan_{{ $plan->id }}" class="text-sm font-medium text-slate-900 cursor-pointer">
                                {{ $plan->codigo }} - {{ $plan->nombre }}
                            </label>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Semestre</label>
                            <input type="number"
                                   name="planes[{{ $plan->id }}][semestre]"
                                   placeholder="Ej: 1"
                                   min="1"
                                   class="w-full rounded-md border-slate-300 shadow-sm focus:border-university-500 focus:ring-university-500 text-sm">
                        </div>

                        <div class="md:col-span-3">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox"
                                       name="planes[{{ $plan->id }}][es_obligatoria]"
                                       class="rounded border-slate-300 text-university-700 shadow-sm focus:ring-university-500">
                                <span class="ml-2 text-sm font-medium text-slate-900">Obligatoria</span>
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Botones --}}
<div class="flex justify-end gap-3 mt-6">
    <a href="{{ route('materias.index') }}"
       class="inline-flex items-center px-6 py-2.5 bg-white border-2 border-slate-300 rounded-md font-medium text-sm text-slate-700 hover:bg-slate-50 transition-colors">
        Cancelar
    </a>

    <button type="submit"
            class="btn-university inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        Guardar Materia
    </button>
</div>