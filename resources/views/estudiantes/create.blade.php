<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Crear Estudiante
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 rounded bg-red-100 text-red-800 p-4">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('estudiantes.store') }}">
                        @csrf

                        @include('estudiantes._form')

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('estudiantes.index') }}"
                               class="px-4 py-2 rounded bg-gray-200 text-gray-800 hover:bg-gray-300">
                                Cancelar
                            </a>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm mb-1">Contraseña</label>
                                    <input
                                        type="password"
                                        name="password"
                                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                                        required
                                    >
                                </div>
                            
                                <div>
                                    <label class="block text-sm mb-1">Confirmar contraseña</label>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700"
                                        required
                                    >
                                </div>
                            </div>


                            <button type="submit"
                                    class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Guardar
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>