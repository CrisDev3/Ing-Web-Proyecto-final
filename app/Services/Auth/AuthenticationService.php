<?php

namespace App\Services\Auth;

use App\Interfaces\AuthenticationInterface;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Class AuthenticationService
 * 
 * Implementación de la interface de autenticación
 * Requisito 2: Clases para cada módulo (Login)
 */
class AuthenticationService implements AuthenticationInterface
{
    /**
     * Autenticar usuario con credenciales
     */
    public function authenticate(string $email, string $password): array
    {
        try {
            // Buscar usuario por email
            $usuario = Usuario::where('email', $email)->first();

            if (!$usuario) {
                return [
                    'success' => false,
                    'user' => null,
                    'message' => 'Las credenciales no coinciden con nuestros registros.',
                ];
            }

            // Verificar si el usuario está activo
            if (!$usuario->activo) {
                return [
                    'success' => false,
                    'user' => null,
                    'message' => 'Su cuenta ha sido desactivada. Contacte al administrador.',
                ];
            }

            // Verificar contraseña
            if (!Hash::check($password, $usuario->password)) {
                return [
                    'success' => false,
                    'user' => null,
                    'message' => 'La contraseña es incorrecta.',
                ];
            }

            // Autenticar con Laravel Auth
            Auth::login($usuario, true);

            return [
                'success' => true,
                'user' => $usuario,
                'message' => 'Inicio de sesión exitoso.',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'user' => null,
                'message' => 'Error al intentar iniciar sesión: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Cerrar sesión del usuario actual
     */
    public function logout(): bool
    {
        try {
            Auth::logout();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verificar si el usuario está autenticado
     */
    public function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Obtener el usuario autenticado actual
     */
    public function getAuthenticatedUser(): ?object
    {
        return Auth::user();
    }

    /**
     * Verificar permisos del usuario
     */
    public function hasPermission(string $permission): bool
    {
        $user = $this->getAuthenticatedUser();
        
        if (!$user) {
            return false;
        }

        // Si es administrador, tiene todos los permisos
        if ($user->esAdministrador()) {
            return true;
        }

        return $user->tienePermiso($permission);
    }

    /**
     * Cambiar contraseña del usuario
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): array
    {
        try {
            $usuario = Usuario::find($userId);

            if (!$usuario) {
                return [
                    'success' => false,
                    'message' => 'Usuario no encontrado.',
                ];
            }

            // Verificar contraseña actual
            if (!Hash::check($currentPassword, $usuario->password)) {
                return [
                    'success' => false,
                    'message' => 'La contraseña actual es incorrecta.',
                ];
            }

            // Validar que la nueva contraseña sea diferente
            if (Hash::check($newPassword, $usuario->password)) {
                return [
                    'success' => false,
                    'message' => 'La nueva contraseña debe ser diferente a la actual.',
                ];
            }

            // Actualizar contraseña
            $usuario->password = Hash::make($newPassword);
            $usuario->save();

            return [
                'success' => true,
                'message' => 'Contraseña actualizada exitosamente.',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al cambiar la contraseña: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Recuperar contraseña (enviar token)
     */
    public function forgotPassword(string $email): array
    {
        try {
            $usuario = Usuario::where('email', $email)->first();

            if (!$usuario) {
                // Por seguridad, no revelamos si el email existe o no
                return [
                    'success' => true,
                    'message' => 'Si el correo existe en nuestro sistema, recibirá un enlace de recuperación.',
                ];
            }

            // Generar token de recuperación
            $token = Password::createToken($usuario);

            // Aquí se enviaría el email con el token
            // Mail::to($usuario->email)->send(new ResetPasswordMail($token));

            return [
                'success' => true,
                'message' => 'Se ha enviado un enlace de recuperación a su correo.',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Resetear contraseña con token
     */
    public function resetPassword(string $token, string $newPassword): array
    {
        try {
            // Verificar token
            $tokenData = \DB::table('password_reset_tokens')
                ->where('token', $token)
                ->first();

            if (!$tokenData) {
                return [
                    'success' => false,
                    'message' => 'El token de recuperación es inválido o ha expirado.',
                ];
            }

            // Buscar usuario
            $usuario = Usuario::where('email', $tokenData->email)->first();

            if (!$usuario) {
                return [
                    'success' => false,
                    'message' => 'Usuario no encontrado.',
                ];
            }

            // Actualizar contraseña
            $usuario->password = Hash::make($newPassword);
            $usuario->save();

            // Eliminar token usado
            \DB::table('password_reset_tokens')
                ->where('token', $token)
                ->delete();

            return [
                'success' => true,
                'message' => 'Contraseña restablecida exitosamente.',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al restablecer la contraseña: ' . $e->getMessage(),
            ];
        }
    }
}
