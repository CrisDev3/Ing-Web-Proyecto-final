<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Sistema de Matrículas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="text-lg font-semibold">
                        Bienvenido, {{ auth()->user()->email }}
                    </div>
                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        Rol: <span class="font-medium">{{ auth()->user()->rol }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                <a href="{{ route('matriculas.crear') }}"
                   class="block p-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg hover:shadow-md transition">
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Matricular</div>
                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">Crear una matrícula para un estudiante.</div>
                </a>

                <a href="{{ route('estudiantes.index') }}"
                   class="block p-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg hover:shadow-md transition">
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Estudiantes</div>
                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">Ver y administrar estudiantes.</div>
                </a>

                <a href="{{ route('docentes.index') }}"
                   class="block p-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg hover:shadow-md transition">
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Docentes</div>
                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">Ver y administrar docentes.</div>
                </a>

                <a href="{{ route('materias.index') }}"
                   class="block p-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg hover:shadow-md transition">
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Materias</div>
                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">Administrar materias y prerequisitos.</div>
                </a>

                <a href="{{ route('grupos.index') }}"
                   class="block p-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg hover:shadow-md transition">
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Grupos</div>
                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">Oferta académica, cupos y periodos.</div>
                </a>

                <a href="{{ route('plan-estudios.index') }}"
                   class="block p-6 bg-white dark:bg-gray-800 shadow-sm rounded-lg hover:shadow-md transition">
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Planes de Estudio</div>
                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-300">Gestionar planes y sus materias.</div>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
