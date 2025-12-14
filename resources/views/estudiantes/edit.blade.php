<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Editar Estudiante
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('estudiantes.update', $estudiante) }}">
                        @csrf
                        @method('PUT')

                        @include('estudiantes._form')

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('estudiantes.index') }}"
                               class="px-4 py-2 rounded bg-gray-200 text-gray-800 hover:bg-gray-300">
                                Cancelar
                            </a>

                            <button type="submit"
                                    class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                                Actualizar
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
