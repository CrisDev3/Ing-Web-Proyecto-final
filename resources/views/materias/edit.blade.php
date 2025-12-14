<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Editar Materia</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto">
        <form method="POST" action="{{ route('materias.update', $materia) }}"
              class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            @method('PUT')
            @include('materias._form')
        </form>
    </div>
</x-app-layout>
