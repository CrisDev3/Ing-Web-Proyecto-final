<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DocenteController extends Controller
{
    public function index(Request $request)
{
    $query = Docente::with('usuario')
        ->orderBy('apellido')
        ->orderBy('nombre');

    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('nombre', 'like', "%{$s}%")
              ->orWhere('apellido', 'like', "%{$s}%")
              ->orWhere('cedula', 'like', "%{$s}%")
              ->orWhere('email', 'like', "%{$s}%")
              ->orWhere('especialidad', 'like', "%{$s}%");
        });
    }

    if ($request->filled('activo')) {
        $query->where('activo', $request->boolean('activo'));
    }

    $docentes = $query->paginate(15)->withQueryString();

    return view('docentes.index', compact('docentes'));
}


    public function create()
    {
        return view('docentes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'required|string|max:20|unique:docentes,cedula',
            'especialidad' => 'nullable|string|max:120',
            'email' => 'required|email|max:100|unique:docentes,email|unique:usuarios,email',
            'telefono' => 'nullable|string|max:20',
            'activo' => 'nullable|boolean',

            // usuario
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data['activo'] = (bool)($data['activo'] ?? false);

        return DB::transaction(function () use ($data) {
            $usuario = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'rol' => User::ROL_DOCENTE,
                'activo' => true,
            ]);

            $docente = Docente::create([
                'usuario_id' => $usuario->id,
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'cedula' => $data['cedula'],
                'especialidad' => $data['especialidad'] ?? null,
                'email' => $data['email'],
                'telefono' => $data['telefono'] ?? null,
                'activo' => $data['activo'],
            ]);

            return redirect()
                ->route('docentes.show', $docente)
                ->with('success', 'Docente creado y usuario generado correctamente.');
        });
    }

    public function show(Docente $docente)
    {
        $docente->load(['usuario', 'grupos.materia', 'grupos.horarios']);
        return view('docentes.show', compact('docente'));
    }

    public function edit(Docente $docente)
    {
        $docente->load('usuario');
        return view('docentes.edit', compact('docente'));
    }

    public function update(Request $request, Docente $docente)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'required|string|max:20|unique:docentes,cedula,' . $docente->id,
            'especialidad' => 'nullable|string|max:120',
            'email' => 'required|email|max:100|unique:docentes,email,' . $docente->id,
            'telefono' => 'nullable|string|max:20',
            'activo' => 'nullable|boolean',

            // opcional: cambiar password del usuario
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data['activo'] = (bool)($data['activo'] ?? false);

        return DB::transaction(function () use ($docente, $data) {
            // si cambia email, también cambia en usuarios (y validar que no choque)
            if ($docente->email !== $data['email']) {
                $existeEnUsuarios = Usuario::where('email', $data['email'])
                    ->where('id', '!=', $docente->usuario_id)
                    ->exists();

                if ($existeEnUsuarios) {
                    return back()->withInput()->with('error', 'Ese email ya está en uso por otro usuario.');
                }

                $docente->usuario?->update(['email' => $data['email']]);
            }

            if (!empty($data['password'])) {
                $docente->usuario?->update(['password' => Hash::make($data['password'])]);
            }

            $docente->update([
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'cedula' => $data['cedula'],
                'especialidad' => $data['especialidad'] ?? null,
                'email' => $data['email'],
                'telefono' => $data['telefono'] ?? null,
                'activo' => $data['activo'],
            ]);

            return redirect()
                ->route('docentes.show', $docente)
                ->with('success', 'Docente actualizado correctamente.');
        });
    }

    public function destroy(Docente $docente)
    {
        if ($docente->grupos()->count() > 0) {
            return back()->with('error', 'No puedes eliminar un docente que tiene grupos asignados.');
        }

        return DB::transaction(function () use ($docente) {
            $usuarioId = $docente->usuario_id;

            $docente->delete();

            if ($usuarioId) {
                Usuario::find($usuarioId)?->delete();
            }

            return redirect()
                ->route('docentes.index')
                ->with('success', 'Docente eliminado correctamente.');
        });
    }

    // Extra route: docentes.toggle-activo
    public function toggleActivo(Docente $docente)
    {
        $docente->activo = !$docente->activo;
        $docente->save();

        return back()->with('success', 'Estado actualizado.');
    }
}
