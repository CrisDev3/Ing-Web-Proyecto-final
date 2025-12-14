<?php

namespace App\Interfaces;

/**
 * Interface AuthenticationInterface
 * 
 * Interface para el manejo de autenticación del sistema
 * Requisito 2: Interfaces en el login
 */
interface AuthenticationInterface
{
    /**
     * Autenticar usuario con credenciales
     * 
     * @param string $email
     * @param string $password
     * @return array ['success' => bool, 'user' => Usuario|null, 'message' => string]
     */
    public function authenticate(string $email, string $password): array;

    /**
     * Cerrar sesión del usuario actual
     * 
     * @return bool
     */
    public function logout(): bool;

    /**
     * Verificar si el usuario está autenticado
     * 
     * @return bool
     */
    public function isAuthenticated(): bool;

    /**
     * Obtener el usuario autenticado actual
     * 
     * @return \App\Models\Usuario|null
     */
    public function getAuthenticatedUser(): ?object;

    /**
     * Verificar permisos del usuario
     * 
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool;

    /**
     * Cambiar contraseña del usuario
     * 
     * @param int $userId
     * @param string $currentPassword
     * @param string $newPassword
     * @return array ['success' => bool, 'message' => string]
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): array;

    /**
     * Recuperar contraseña (enviar token)
     * 
     * @param string $email
     * @return array ['success' => bool, 'message' => string]
     */
    public function forgotPassword(string $email): array;

    /**
     * Resetear contraseña con token
     * 
     * @param string $token
     * @param string $newPassword
     * @return array ['success' => bool, 'message' => string]
     */
    public function resetPassword(string $token, string $newPassword): array;
}
