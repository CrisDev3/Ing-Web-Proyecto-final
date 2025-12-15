<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Docente;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index(Request $request)
{
    $query = Grupo::with(['materia', 'docente'])->orderByDesc('id');

    if ($request->filled('search')) {
        $s = $request->search;

        $query->where(function ($q) use ($s) {
            $q->where('codigo', 'like', "%{$s}%")
              ->orWhere('periodo_academico', 'like', "%{$s}%");
        });
    }

    if ($request->filled('materia_id')) {
        $query->where('materia_id', $request->materia_id);
    }

    if ($request->filled('docente_id')) {
        $query->where('docente_id', $request->docente_id);
    }

    if ($request->filled('activo')) {
        $query->where('activo', (int)$request->activo);
    }

    $grupos = $query->paginate(15)->withQueryString();

    $materias = Materia::orderBy('nombre')->get();
    $docentes = Docente::orderBy('apellido')->orderBy('nombre')->get();

    return view('grupos.index', compact('grupos', 'materias', 'docentes'));
}


    public function create()
    {
        $materias = Materia::orderBy('nombre')->get();
        $docentes = Docente::orderBy('apellido')->orderBy('nombre')->get();

        return view('grupos.create', compact('materias', 'docentes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:20|unique:grupos,codigo',
            'materia_id' => 'required|exists:materias,id',
            'docente_id' => 'nullable|exists:docentes,id',
            'periodo_academico' => 'required|string|max:20',
            'cupo_maximo' => 'required|integer|min:1|max:200',
            'activo' => 'nullable|boolean',
        ]);

        $data['activo'] = (bool)($data['activo'] ?? false);
        $data['cupo_actual'] = 0;

        $grupo = Grupo::create($data);

        return redirect()
            ->route('grupos.show', $grupo)
            ->with('success', 'Grupo creado correctamente.');
    }

    public function show(Grupo $grupo)
    {
        $grupo->load(['materia', 'docente', 'horarios', 'matriculas']);

        return view('grupos.show', compact('grupo'));
    }

    public function edit(Grupo $grupo)
    {
        $materias = Materia::orderBy('nombre')->get();
        $docentes = Docente::orderBy('apellido')->orderBy('nombre')->get();

        return view('grupos.edit', compact('grupo', 'materias', 'docentes'));
    }

    public function update(Request $request, Grupo $grupo)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:20|unique:grupos,codigo,' . $grupo->id,
            'materia_id' => 'required|exists:materias,id',
            'docente_id' => 'nullable|exists:docentes,id',
            'periodo_academico' => 'required|string|max:20',
            'cupo_maximo' => 'required|integer|min:1|max:200',
            'activo' => 'nullable|boolean',
        ]);

        $data['activo'] = (bool)($data['activo'] ?? false);

        // opcional: si cupo_maximo baja por debajo de cupo_actual, bloquea
        if ($data['cupo_maximo'] < $grupo->cupo_actual) {
            return back()->withInput()->with('error', 'El cupo máximo no puede ser menor que el cupo actual.');
        }

        $grupo->update($data);

        return redirect()
            ->route('grupos.show', $grupo)
            ->with('success', 'Grupo actualizado correctamente.');
    }

    public function destroy(Grupo $grupo)
    {
        if ($grupo->matriculas()->count() > 0) {
            return back()->with('error', 'No puedes eliminar un grupo con matrículas registradas.');
        }

        $grupo->delete();

        return redirect()
            ->route('grupos.index')
            ->with('success', 'Grupo eliminado.');
    }
}