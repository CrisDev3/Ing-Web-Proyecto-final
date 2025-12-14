<?php

namespace App\Http\Controllers;

use App\Models\PlanEstudios;
use App\Models\Materia;
use Illuminate\Http\Request;

class PlanEstudiosController extends Controller
{
    public function index()
    {
        $planes = PlanEstudios::orderBy('nombre')->paginate(10);
        return view('plan-estudios.index', compact('planes'));
    }

    public function create()
    {
        return view('plan-estudios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:150',
            'codigo' => 'required|string|max:20|unique:plan_estudios,codigo',
            'descripcion' => 'nullable|string',
            'duracion_semestres' => 'required|integer|min:1',
        ]);

        PlanEstudios::create($data + ['activo' => true]);

        return redirect()->route('plan-estudios.index')
            ->with('success', 'Plan creado correctamente');
    }

    public function show(PlanEstudios $plan_estudio)
{
    $plan_estudio->load(['materias' => function ($q) {
        $q->orderBy('materia_plan_estudios.semestre')
          ->orderBy('materias.nombre');
    }]);

    $idsAsignadas = $plan_estudio->materias->pluck('id');

    $materiasDisponibles = Materia::whereNotIn('id', $idsAsignadas)
        ->orderBy('nombre')
        ->get();

    return view('plan-estudios.show', [
        'plan' => $plan_estudio,
        'materiasDisponibles' => $materiasDisponibles,
    ]);
}

public function edit(PlanEstudios $plan_estudio)
{
    return view('plan-estudios.edit', ['plan' => $plan_estudio]);
}

public function update(Request $request, PlanEstudios $plan_estudio)
{
    $data = $request->validate([
        'nombre' => 'required|string|max:150',
        'codigo' => 'required|string|max:20|unique:plan_estudios,codigo,' . $plan_estudio->id,
        'descripcion' => 'nullable|string',
        'duracion_semestres' => 'required|integer|min:1',
        'activo' => 'boolean',
    ]);

    $data['activo'] = $request->has('activo'); // checkbox

    $plan_estudio->update($data);

    return redirect()->route('plan-estudios.index')->with('success', 'Plan actualizado');
}

    public function destroy(PlanEstudios $plan_estudio)
    {
        if ($plan_estudio->estudiantes()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un plan con estudiantes.');
        }

        $plan_estudio->delete();

        return back()->with('success', 'Plan eliminado');
    }

        public function materias(PlanEstudios $plan)
    {
        // Materias ya asignadas al plan
        $materiasAsignadas = $plan->materias()
        ->orderBy('pivot_semestre')
        ->get();
    
        $materiasDisponibles = Materia::orderBy('nombre')->get();
    
        return view('plan-estudios.materias', compact('plan', 'materiasAsignadas','materiasDisponibles'));
    }
    
    public function agregarMateria(Request $request, PlanEstudios $plan)
    {
        $data = $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'semestre' => 'required|integer|min:1|max:3',
            'es_obligatoria' => 'nullable|boolean',
        ]);
    
        $ya = $plan->materiasAsignadas()->where('materias.id', $data['materia_id'])->exists();
        if ($ya) {
            return back()->with('error', 'Esa materia ya está asignada a este plan.');
        }
    
        $plan->materiasAsignadas()->attach($data['materia_id'], [
            'semestre' => $data['semestre'],
            'es_obligatoria' => (bool)($data['es_obligatoria'] ?? false),
        ]);
    
        return back()->with('success', 'Materia asignada correctamente.');
    }
    
    public function removerMateria(PlanEstudios $plan, Materia $materia)
    {
        $plan->materiasAsignadas()->detach($materia->id);
        return back()->with('success', 'Materia removida del plan.');
    }
    

    public function actualizarMateria(Request $request, PlanEstudios $plan_estudio, Materia $materia)
    {
        $data = $request->validate([
            'semestre' => 'required|integer|min:1|max:3',
            'es_obligatoria' => 'nullable|boolean',
        ]);

        $plan_estudio->materias()->updateExistingPivot($materia->id, [
            'semestre' => $data['semestre'],
            'es_obligatoria' => (bool)($data['es_obligatoria'] ?? false),
        ]);

        return back()->with('success', 'Materia actualizada.');
    }

    public function obtenerMaterias(PlanEstudios $plan_estudio)
    {
        // Para tu API interna (ajax)
        return response()->json(
            $plan_estudio->materiasAsignadas()
                ->select('materias.id', 'materias.nombre', 'materias.codigo', 'materia_plan_estudios.semestre', 'materia_plan_estudios.es_obligatoria')
                ->orderBy('materia_plan_estudios.semestre')
                ->orderBy('materias.nombre')
                ->get()
        );
    }

}