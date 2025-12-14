@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm">Código</label>
        <input name="codigo" class="w-full rounded border p-2"
               value="{{ old('codigo', $materia->codigo ?? '') }}" required>
    </div>

    <div>
        <label class="block text-sm">Nombre</label>
        <input name="nombre" class="w-full rounded border p-2"
               value="{{ old('nombre', $materia->nombre ?? '') }}" required>
    </div>

    <div>
        <label class="block text-sm">Créditos</label>
        <input type="number" name="creditos" class="w-full rounded border p-2"
               value="{{ old('creditos', $materia->creditos ?? '') }}" required>
    </div>

    <div>
        <label class="block text-sm">Horas Teóricas</label>
        <input type="number" name="horas_teoricas" class="w-full rounded border p-2"
               value="{{ old('horas_teoricas', $materia->horas_teoricas ?? 0) }}">
    </div>

    <div>
        <label class="block text-sm">Horas Prácticas</label>
        <input type="number" name="horas_practicas" class="w-full rounded border p-2"
               value="{{ old('horas_practicas', $materia->horas_practicas ?? 0) }}">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm">Descripción</label>
        <textarea name="descripcion" rows="3"
                  class="w-full rounded border p-2">{{ old('descripcion', $materia->descripcion ?? '') }}</textarea>
    </div>
</div>

<div class="mt-6">
    <h3 class="font-semibold mb-2">Asignar a Planes de Estudio</h3>

    <div class="space-y-2">
        @foreach($planes as $plan)
            <div class="flex items-center gap-4">
                <input type="checkbox"
                       name="planes[{{ $plan->id }}][id]"
                       {{ isset($materia) && $materia->planesEstudios->contains($plan->id) ? 'checked' : '' }}>

                <span>{{ $plan->nombre }}</span>

                <input type="number"
                       name="planes[{{ $plan->id }}][semestre]"
                       placeholder="Semestre"
                       class="w-24 rounded border p-1">

                <label class="flex items-center gap-1 text-sm">
                    <input type="checkbox"
                           name="planes[{{ $plan->id }}][es_obligatoria]">
                    Obligatoria
                </label>
            </div>
        @endforeach
    </div>
</div>

<div class="mt-6">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        Guardar
    </button>
</div>
