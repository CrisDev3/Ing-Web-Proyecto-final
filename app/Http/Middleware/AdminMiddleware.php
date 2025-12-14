<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) abort(403);

        if (property_exists($user, 'activo') && !$user->activo) {
            abort(403, 'Usuario inactivo');
        }

        if (!isset($user->rol) || $user->rol !== 'administrador') {
            abort(403, 'Solo administradores');
        }
        
        return $next($request);
    }
}
