<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Horarios
            </h2>

            <a href="{{ route('horarios.create') }}"
               class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                + Nuevo Horario
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
            @endif

            {{-- Filtros --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <form method="GET" action="{{ route('horarios.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Buscar</label>
                        <input name="search" value="{{ request('search') }}"
                               placeholder="Día, tipo o hora..."
                               class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Día</label>
                        <select name="dia" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            <option value="">Todos</option>
                            @foreach($dias as $d)
                                <option value="{{ $d }}" {{ request('dia') === $d ? 'selected' : '' }}>{{ $d }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm mb-1 text-gray-700 dark:text-gray-200">Tipo</label>
                        <select name="tipo" class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700">
                            <option value="">Todos</option>
                            @foreach($tipos as $t)
                                <option value="{{ $t }}" {{ request('tipo') === $t ? 'selected' : '' }}>
                                    {{ ucfirst($t) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button class="px-4 py-2 rounded bg-blue-600 text-white">Filtrar</button>
                        <a href="{{ route('horarios.index') }}"
                           class="px-4 py-2 rounded bg-gray-200 text-gray-900 hover:bg-gray-300">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabla --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left border-b dark:border-gray-700">
                            <tr>
                                <th class="py-2 pr-4">Día</th>
                                <th class="py-2 pr-4">Inicio</th>
                                <th class="py-2 pr-4">Fin</th>
                                <th class="py-2 pr-4">Tipo</th>
                                <th class="py-2 pr-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($horarios as $h)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="py-2 pr-4">{{ $h->dia }}</td>
                                    <td class="py-2 pr-4">{{ $h->hora_inicio }}</td>
                                    <td class="py-2 pr-4">{{ $h->hora_fin }}</td>
                                    <td class="py-2 pr-4">{{ ucfirst($h->tipo) }}</td>
                                    <td class="py-2 pr-4">
                                        <div class="flex justify-end gap-3">
                                            <a class="underline text-indigo-600" href="{{ route('horarios.show', $h) }}">Ver</a>
                                            <a class="underline text-blue-600" href="{{ route('horarios.edit', $h) }}">Editar</a>
                                            <form method="POST" action="{{ route('horarios.destroy', $h) }}"
                                                  onsubmit="return confirm('¿Seguro que deseas eliminar este horario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="underline text-red-600" type="submit">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-gray-500">No hay horarios.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $horarios->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
