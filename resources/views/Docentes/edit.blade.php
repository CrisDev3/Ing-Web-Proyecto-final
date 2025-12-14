<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Editar Docente: {{ $docente->nombre }} {{ $docente->apellido }}
            </h2>
            <a href="{{ route('docentes.index') }}"
               class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <form method="POST" action="{{ route('docentes.update', $docente) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Nombre</label>
                        <input name="nombre" value="{{ old('nombre', $docente->nombre) }}" required
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Apellido</label>
                        <input name="apellido" value="{{ old('apellido', $docente->apellido) }}" required
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Cédula</label>
                        <input name="cedula" value="{{ old('cedula', $docente->cedula) }}" required
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Especialidad</label>
                        <input name="especialidad" value="{{ old('especialidad', $docente->especialidad) }}"
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Email (login)</label>
                        <input type="email" name="email" value="{{ old('email', $docente->email) }}" required
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Teléfono</label>
                        <input name="telefono" value="{{ old('telefono', $docente->telefono) }}"
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div class="flex items-center gap-2 mt-6">
                        <input type="checkbox" name="activo" value="1" {{ old('activo', $docente->activo) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700 dark:text-gray-200">Activo</span>
                    </div>

                    <div class="md:col-span-2 border-t dark:border-gray-700 pt-4 mt-2">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">
                            Cambiar Password (opcional)
                        </h3>
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Nuevo Password</label>
                        <input type="password" name="password"
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Confirmar Password</label>
                        <input type="password" name="password_confirmation"
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div class="md:col-span-2 flex gap-2 pt-2">
                        <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                            Actualizar
                        </button>
                        <a href="{{ route('docentes.show', $docente) }}"
                           class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
