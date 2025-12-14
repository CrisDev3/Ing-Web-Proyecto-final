<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Grupo;
use App\Models\Matricula;
use App\Models\Materia; // ✅ FALTABA
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    // (Admin) Formulario para matricular a cualquier estudiante
    public function crear()
    {
        $estudiantes = Estudiante::orderBy('apellido')->orderBy('nombre')->get();
        $grupos = Grupo::with('materia')->where('activo', true)->get();

        return view('matriculas.crear', compact('estudiantes', 'grupos'));
    }

    // (Admin) Guardar matrícula
    public function matricular(Request $request)
    {
        $data = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'grupo_id' => 'required|exists:grupos,id',
        ]);

        $grupo = Grupo::findOrFail($data['grupo_id']);

        if ($grupo->cupo_actual >= $grupo->cupo_maximo) {
            return back()->with('error', 'Ese grupo ya está lleno.');
        }

        $ya = Matricula::where('estudiante_id', $data['estudiante_id'])
            ->where('grupo_id', $data['grupo_id'])
            ->exists();

        if ($ya) {
            return back()->with('error', 'Ese estudiante ya está matriculado en ese grupo.');
        }

        Matricula::create([
            'estudiante_id' => $data['estudiante_id'],
            'grupo_id' => $data['grupo_id'],
            'fecha_matricula' => now()->toDateString(),
            'estado' => Matricula::ESTADO_ACTIVA,
        ]);

        $grupo->increment('cupo_actual');

        return back()->with('success', 'Matrícula realizada.');
    }

    // (Estudiante) Pantalla: Materia -> Grupos disponibles
    public function autoMatricula(Request $request)
    {
        $user = $request->user();

        // ✅ Si tu relación no se llama estudiante(), esto fallará.
        $estudiante = $user->estudiante;

        if (!$estudiante) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu usuario no está vinculado a un estudiante.');
        }

        $materias = Materia::where('plan_estudios_id', $estudiante->plan_estudios_id)
            ->orderBy('nombre')
            ->get();

        $materiaId = $request->query('materia_id');
        $grupos = collect();

        if ($materiaId) {
            $grupos = Grupo::with(['materia', 'docente', 'horarios'])
                ->where('materia_id', $materiaId)
                ->where('activo', true)
                ->whereColumn('cupo_actual', '<', 'cupo_maximo')
                ->get();
        }

        return view('mi.matricular', compact('estudiante', 'materias', 'materiaId', 'grupos'));
    }

    // (Estudiante) Guardar matrícula del usuario logueado
    public function autoMatricularStore(Request $request)
    {
        $user = $request->user();
        $estudiante = $user->estudiante;

        if (!$estudiante) {
            return back()->with('error', 'No estás vinculado a un estudiante.');
        }

        $data = $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
        ]);

        $grupo = Grupo::findOrFail($data['grupo_id']);

        if ($grupo->cupo_actual >= $grupo->cupo_maximo) {
            return back()->with('error', 'Ese grupo ya está lleno.');
        }
        if (!$grupo->activo) {
        return back()->with('error', 'Ese grupo no está activo.');
    }
        // Evitar duplicado
        $ya = Matricula::where('estudiante_id', $estudiante->id)
            ->where('grupo_id', $grupo->id)
            ->exists();

        if ($ya) {
            return back()->with('error', 'Ya estás matriculado en ese grupo.');
        }

        Matricula::create([
            'estudiante_id' => $estudiante->id,
            'grupo_id' => $grupo->id,
            'fecha_matricula' => now()->toDateString(),
            'estado' => Matricula::ESTADO_ACTIVA,
        ]);

        $grupo->increment('cupo_actual');

        return redirect()->route('mi.matricular',  ['materia_id' => $grupo->materia_id])
            ->with('success', '¡Matrícula realizada!');
    }
}