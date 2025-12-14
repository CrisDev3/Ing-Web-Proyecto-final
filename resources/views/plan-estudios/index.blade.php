<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Planes de Estudio
            </h2>

            <a href="{{ route('plan-estudios.create') }}"
               class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                + Nuevo Plan
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left border-b dark:border-gray-700">
                                <tr>
                                    <th class="py-2 pr-4">Código</th>
                                    <th class="py-2 pr-4">Nombre</th>
                                    <th class="py-2 pr-4">Duración</th>
                                    <th class="py-2 pr-4">Activo</th>
                                    <th class="py-2 pr-4">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($planes as $plan)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="py-2 pr-4">{{ $plan->codigo }}</td>
                                        <td class="py-2 pr-4">{{ $plan->nombre }}</td>
                                        <td class="py-2 pr-4">{{ $plan->duracion_semestres }} sem.</td>
                                        <td class="py-2 pr-4">
                                            @if($plan->activo)
                                                <span class="px-2 py-1 rounded bg-green-200 text-green-900">Sí</span>
                                            @else
                                                <span class="px-2 py-1 rounded bg-gray-200 text-gray-900">No</span>
                                            @endif
                                        </td>

                                        <td class="py-2 pr-4">
                                            <div class="flex gap-3 flex-wrap">
                                                <a class="underline text-indigo-600 hover:text-indigo-800"
                                                   href="{{ route('plan-estudios.show', $plan) }}">
                                                    Ver
                                                </a>

                                                <a class="underline text-blue-600 hover:text-blue-800"
                                                   href="{{ route('plan-estudios.edit', $plan) }}">
                                                    Editar
                                                </a>

                                                {{-- ✅ Botón para asignar/ver materias del plan --}}
                                                <a class="underline text-emerald-600 hover:text-emerald-800"
                                                   href="{{ route('plan-estudios.materias', $plan) }}">
                                                    Materias
                                                </a>

                                                <form action="{{ route('plan-estudios.destroy', $plan) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('¿Seguro que deseas eliminar este plan?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="underline text-red-600 hover:text-red-800" type="submit">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="py-4" colspan="5">
                                            No hay planes registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $planes->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>

