<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            {{ $estudiante->nombre_completo }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <p><strong>Email:</strong> {{ $estudiante->email }}</p>
                <p><strong>Cédula:</strong> {{ $estudiante->cedula }}</p>
                <p><strong>Plan:</strong> {{ $estudiante->planEstudios->nombre }}</p>

                <div class="mt-4">
                    <a href="{{ route('estudiantes.horario', $estudiante) }}"
                       class="text-indigo-600 underline">
                        Ver horario
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
