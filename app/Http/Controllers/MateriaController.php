<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\PlanEstudios;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    /**
     * Listado de materias
     */
    public function index()
    {
        $materias = Materia::with('planesEstudios')
            ->orderBy('nombre')
            ->paginate(10);

        return view('materias.index', compact('materias'));
    }

    /**
     * Formulario crear materia
     */
    public function create()
    {
        $planes = PlanEstudios::activos()->orderBy('nombre')->get();

        return view('materias.create', compact('planes'));
    }

    /**
     * Guardar materia
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:20|unique:materias,codigo',
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:1',
            'horas_teoricas' => 'required|integer|min:0',
            'horas_practicas' => 'required|integer|min:0',
            'planes' => 'array', // planes seleccionados
        ]);

        $materia = Materia::create($data);

        // Asociar planes (pivot)
        if ($request->filled('planes')) {
            foreach ($request->planes as $planId => $pivotData) {
                $materia->planesEstudios()->attach($planId, [
                    'semestre' => $pivotData['semestre'] ?? 1,
                    'es_obligatoria' => isset($pivotData['es_obligatoria']),
                ]);
            }
        }

        return redirect()
            ->route('materias.index')
            ->with('success', 'Materia creada correctamente.');
    }

    /**
     * Ver materia
     */
    public function show(Materia $materia)
    {
        $materia->load(['planesEstudios', 'prerequisitos', 'dependientes']);

        return view('materias.show', compact('materia'));
    }

    /**
     * Formulario editar materia
     */
    public function edit(Materia $materia)
    {
        $planes = PlanEstudios::activos()->orderBy('nombre')->get();

        return view('materias.edit', compact('materia', 'planes'));
    }

    /**
     * Actualizar materia
     */
    public function update(Request $request, Materia $materia)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:20|unique:materias,codigo,' . $materia->id,
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:1',
            'horas_teoricas' => 'required|integer|min:0',
            'horas_practicas' => 'required|integer|min:0',
        ]);

        $materia->update($data);

        return redirect()
            ->route('materias.index')
            ->with('success', 'Materia actualizada.');
    }

    /**
     * Eliminar materia
     */
    public function destroy(Materia $materia)
    {
        $materia->delete();

        return redirect()
            ->route('materias.index')
            ->with('success', 'Materia eliminada.');
    }

    /**
     * Buscar materias (AJAX)
     */
    public function buscar(Request $request)
    {
        return Materia::where('nombre', 'like', "%{$request->q}%")
            ->orWhere('codigo', 'like', "%{$request->q}%")
            ->limit(10)
            ->get();
    }

    /**
     * Grupos disponibles por materia (para matrícula)
     */
    public function gruposDisponibles(Materia $materia)
    {
        return $materia->grupos()
            ->where('activo', true)
            ->whereColumn('cupo_actual', '<', 'cupo_maximo')
            ->with(['docente', 'horarios'])
            ->get();
    }
}
