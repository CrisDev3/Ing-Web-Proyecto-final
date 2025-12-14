<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ $materia->nombre }}</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto bg-white dark:bg-gray-800 p-6 rounded shadow">
        <p><strong>Código:</strong> {{ $materia->codigo }}</p>
        <p><strong>Créditos:</strong> {{ $materia->creditos }}</p>
        <p><strong>Horas Totales:</strong> {{ $materia->total_horas }}</p>

        <div class="mt-4">
            <h3 class="font-semibold">Planes de Estudio</h3>
            <ul class="list-disc ml-5">
                @foreach($materia->planesEstudios as $plan)
                    <li>
                        {{ $plan->nombre }}
                        (Semestre {{ $plan->pivot->semestre }})
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
