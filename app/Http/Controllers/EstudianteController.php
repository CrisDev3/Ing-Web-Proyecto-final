<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\User;
use App\Models\PlanEstudios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    
    public function index(Request $request)
{
    $query = Estudiante::with(['planEstudios', 'usuario']);

    // Búsqueda (solo si viene con texto)
    if ($request->filled('search')) {
        $search = trim($request->search);
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
              ->orWhere('apellido', 'like', "%{$search}%")
              ->orWhere('cedula', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Filtro por plan (solo si viene seleccionado)
    if ($request->filled('plan_id')) {
        $query->where('plan_estudios_id', $request->plan_id);
    }

    // Filtro por estado (acepta "1" y "0")
    if ($request->has('activo') && $request->activo !== '') {
        $query->where('activo', (int)$request->activo);
    }

    $estudiantes = $query->orderBy('apellido')
        ->orderBy('nombre')
        ->paginate(15)
        ->withQueryString();

    $planes = PlanEstudios::activos()->get();

    return view('estudiantes.index', compact('estudiantes', 'planes'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $planes = PlanEstudios::activos()->get();
        return view('estudiantes.create', compact('planes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:100',
        'apellido' => 'required|string|max:100',
        'cedula' => 'required|string|max:20|unique:estudiantes,cedula',
        'email' => 'required|email|max:100|unique:estudiantes,email|unique:usuarios,email',
        'telefono' => 'nullable|string|max:20',
        'direccion' => 'nullable|string',
        'plan_estudios_id' => 'required|exists:plan_estudios,id',
        'password' => 'required|min:8|confirmed',
    ]);

    DB::beginTransaction();

    try {
        // 1) Crear usuario SIEMPRE
        $usuario = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol' => User::ROL_ESTUDIANTE,
            'activo' => true,
        ]);

        // 2) Crear estudiante vinculado
        $estudiante = Estudiante::create([
            'usuario_id' => $usuario->id,
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'cedula' => $validated['cedula'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
            'plan_estudios_id' => $validated['plan_estudios_id'],
            'activo' => true,
        ]);

        DB::commit();

        return redirect()
            ->route('estudiantes.show', $estudiante)
            ->with('success', 'Estudiante y usuario creados exitosamente.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()
            ->withInput()
            ->with('error', 'Error al crear el estudiante: ' . $e->getMessage());
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Estudiante $estudiante)
    {
        $estudiante->load([
            'planEstudios',
            'usuario',
            'matriculas.grupo.materia',
            'matriculas.grupo.docente',
        ]);

        // Obtener horario actual
        $horario = $estudiante->obtenerHorario();

        // Calcular estadísticas
        $cargaAcademica = $estudiante->cargaAcademica();
        $matriculasActivas = $estudiante->matriculasActivas()->count();
        $materiasAprobadas = $estudiante->matriculas()
            ->where('estado', 'aprobada')
            ->count();

        return view('estudiantes.show', compact(
            'estudiante',
            'horario',
            'cargaAcademica',
            'matriculasActivas',
            'materiasAprobadas'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estudiante $estudiante)
    {
        $planes = PlanEstudios::activos()->get();
        return view('estudiantes.edit', compact('estudiante', 'planes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estudiante $estudiante)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'required|string|max:20|unique:estudiantes,cedula,' . $estudiante->id,
            'email' => 'required|email|max:100|unique:estudiantes,email,' . $estudiante->id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'plan_estudios_id' => 'required|exists:plan_estudios,id',
            'activo' => 'boolean',
        ]);

        $estudiante->update($validated);

        return redirect()
            ->route('estudiantes.show', $estudiante)
            ->with('success', 'Estudiante actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        DB::beginTransaction();

        try {
            // Verificar que no tenga matrículas activas
            if ($estudiante->matriculasActivas()->count() > 0) {
                return back()->with('error', 'No se puede eliminar un estudiante con matrículas activas.');
            }

            $usuarioId = $estudiante->usuario_id;

            $estudiante->delete();

            // Eliminar usuario asociado si existe
            if ($usuarioId) {
                Usuario::find($usuarioId)?->delete();
            }

            DB::commit();

            return redirect()
                ->route('estudiantes.index')
                ->with('success', 'Estudiante eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el estudiante: ' . $e->getMessage());
        }
    }

    /**
     * Activar/Desactivar estudiante
     */
    public function toggleActivo(Estudiante $estudiante)
    {
        $estudiante->activo = !$estudiante->activo;
        $estudiante->save();

        $estado = $estudiante->activo ? 'activado' : 'desactivado';

        return back()->with('success', "Estudiante {$estado} exitosamente.");
    }

    /**
     * Ver horario del estudiante
     */
    public function horario(Estudiante $estudiante)
    {
        $horario = $estudiante->obtenerHorario();
        return view('estudiantes.horario', compact('estudiante', 'horario'));
    }

}

