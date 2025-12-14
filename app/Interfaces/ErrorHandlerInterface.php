<?php

namespace App\Interfaces;

use Exception;

/**
 * Interface ErrorHandlerInterface
 * 
 * Interface para el manejo centralizado de errores del sistema
 * Requisito 2: Interfaces en control de errores
 */
interface ErrorHandlerInterface
{
    /**
     * Manejar una excepción
     * 
     * @param Exception $exception
     * @param string $context Contexto donde ocurrió el error
     * @return array ['success' => false, 'message' => string, 'code' => int]
     */
    public function handleException(Exception $exception, string $context = ''): array;

    /**
     * Registrar error en log
     * 
     * @param string $message
     * @param string $level (error, warning, info)
     * @param array $context
     * @return void
     */
    public function logError(string $message, string $level = 'error', array $context = []): void;

    /**
     * Validar datos y retornar errores
     * 
     * @param array $data
     * @param array $rules
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validate(array $data, array $rules): array;

    /**
     * Obtener mensaje de error amigable para el usuario
     * 
     * @param Exception $exception
     * @return string
     */
    public function getUserFriendlyMessage(Exception $exception): string;

    /**
     * Determinar si un error es crítico
     * 
     * @param Exception $exception
     * @return bool
     */
    public function isCriticalError(Exception $exception): bool;

    /**
     * Enviar notificación de error crítico
     * 
     * @param Exception $exception
     * @param array $context
     * @return void
     */
    public function notifyCriticalError(Exception $exception, array $context = []): void;

    /**
     * Obtener stack trace formateado
     * 
     * @param Exception $exception
     * @return array
     */
    public function getFormattedStackTrace(Exception $exception): array;
}
