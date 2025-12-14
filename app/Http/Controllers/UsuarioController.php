<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Estudiante;
use App\Models\Docente;
use App\Models\PlanEstudios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Listado de usuarios
     */
    public function index()
    {
        $usuarios = Usuario::orderBy('created_at', 'desc')->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $planes = PlanEstudios::where('activo', true)->orderBy('nombre')->get();
        return view('usuarios.create', compact('planes'));
    }

    /**
     * Guardar usuario
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:8|confirmed',
            'rol' => 'required|in:administrador,estudiante,docente',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',

             'plan_estudios_id' => 'required_if:rol,estudiante|nullable|exists:plan_estudios,id',
        ]);

        // 1️⃣ Crear usuario
        $usuario = Usuario::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol' => $data['rol'],
            'activo' => true,
        ]);

        // 2️⃣ Crear perfil según rol
        if ($data['rol'] === Usuario::ROL_ESTUDIANTE) {
            Estudiante::create([
                'usuario_id' => $usuario->id,
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'email' => $data['email'],
                'cedula' => uniqid('EST-'),
                'plan_estudios_id' => $data['plan_estudios_id'],
                'activo' => true,
            ]);
        }

        if ($data['rol'] === Usuario::ROL_DOCENTE) {
            Docente::create([
                'usuario_id' => $usuario->id,
                'nombre' => $data['nombre'],
                'apellido' => $data['apellido'],
                'email' => $data['email'],
                'cedula' => uniqid('DOC-'),
                'activo' => true,
            ]);
        }

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    /**
     * Activar / Desactivar usuario
     */
    public function toggleActivo(Usuario $usuario)
    {
        $usuario->activo = ! $usuario->activo;
        $usuario->save();

        return back()->with('success', 'Estado del usuario actualizado');
    }

    /**
     * Eliminar usuario
     */
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();

        return back()->with('success', 'Usuario eliminado');
    }
}
