<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstudianteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Debe estar autenticado
        if (!$user) {
            abort(403);
        }

        // Debe estar activo
        if (property_exists($user, 'activo') && !$user->activo) {
            abort(403, 'Usuario inactivo');
        }

        // Debe ser rol estudiante
        if (!isset($user->rol) || $user->rol !== 'estudiante') {
            abort(403, 'Solo estudiantes');
        }

        // Debe tener registro en tabla estudiantes
        // si tienes relación: $user->estudiante()
        if (method_exists($user, 'estudiante') && !$user->estudiante) {
            abort(403, 'Tu usuario no está vinculado a un estudiante');
        }

        return $next($request);
    }
}