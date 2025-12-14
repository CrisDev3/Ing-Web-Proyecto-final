<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Crear Horario
            </h2>
            <a href="{{ route('horarios.index') }}"
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
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <form method="POST" action="{{ route('horarios.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Día</label>
                        <select name="dia" required class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            @foreach($dias as $d)
                                <option value="{{ $d }}" {{ old('dia') === $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Tipo</label>
                        <select name="tipo" required class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            @foreach($tipos as $t)
                                <option value="{{ $t }}" {{ old('tipo') === $t ? 'selected' : '' }}>
                                    {{ ucfirst($t) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Hora inicio</label>
                        <input type="time" name="hora_inicio" value="{{ old('hora_inicio') }}" required
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Hora fin</label>
                        <input type="time" name="hora_fin" value="{{ old('hora_fin') }}" required
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div class="md:col-span-2 flex gap-2 pt-2">
                        <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                            Guardar
                        </button>
                        <a href="{{ route('horarios.index') }}"
                           class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>

