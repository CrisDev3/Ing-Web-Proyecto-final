<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div>
        <label class="block text-sm font-medium">Nombre</label>
        <input type="text" name="nombre"
               value="{{ old('nombre', $estudiante->nombre ?? '') }}"
               class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
    </div>

    <div>
        <label class="block text-sm font-medium">Apellido</label>
        <input type="text" name="apellido"
               value="{{ old('apellido', $estudiante->apellido ?? '') }}"
               class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
    </div>

    <div>
        <label class="block text-sm font-medium">Cédula</label>
        <input type="text" name="cedula"
               value="{{ old('cedula', $estudiante->cedula ?? '') }}"
               class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
    </div>

    <div>
        <label class="block text-sm font-medium">Email</label>
        <input type="email" name="email"
               value="{{ old('email', $estudiante->email ?? '') }}"
               class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
    </div>

    <div>
        <label class="block text-sm font-medium">Teléfono</label>
        <input type="text" name="telefono"
               value="{{ old('telefono', $estudiante->telefono ?? '') }}"
               class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
    </div>

    <div>
        <label class="block text-sm font-medium">Plan de estudios</label>
        <select name="plan_estudios_id"
                class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">
            <option value="">Seleccione</option>
            @foreach ($planes as $plan)
                <option value="{{ $plan->id }}"
                    {{ old('plan_estudios_id', $estudiante->plan_estudios_id ?? '') == $plan->id ? 'selected' : '' }}>
                    {{ $plan->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium">Dirección</label>
        <textarea name="direccion" rows="3"
                  class="mt-1 w-full rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600">{{ old('direccion', $estudiante->direccion ?? '') }}</textarea>
    </div>

</div>
