<?php

namespace App\Services\Error;

use App\Interfaces\ErrorHandlerInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

/**
 * Class ErrorHandler
 * 
 * Implementación de la interface de manejo de errores
 * Requisito 2: Clases para control de errores
 */
class ErrorHandler implements ErrorHandlerInterface
{
    /**
     * Códigos de error personalizados
     */
    const ERROR_VALIDATION = 1001;
    const ERROR_DATABASE = 1002;
    const ERROR_AUTHENTICATION = 1003;
    const ERROR_AUTHORIZATION = 1004;
    const ERROR_NOT_FOUND = 1005;
    const ERROR_BUSINESS_LOGIC = 1006;
    const ERROR_EXTERNAL_SERVICE = 1007;
    const ERROR_UNKNOWN = 9999;

    /**
     * Manejar una excepción
     */
    public function handleException(Exception $exception, string $context = ''): array
    {
        // Registrar en log
        $this->logError(
            $exception->getMessage(),
            'error',
            [
                'context' => $context,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ]
        );

        // Obtener código de error apropiado
        $errorCode = $this->getErrorCode($exception);

        // Obtener mensaje amigable
        $userMessage = $this->getUserFriendlyMessage($exception);

        // Verificar si es crítico
        if ($this->isCriticalError($exception)) {
            $this->notifyCriticalError($exception, ['context' => $context]);
        }

        return [
            'success' => false,
            'message' => $userMessage,
            'code' => $errorCode,
            'debug' => config('app.debug') ? [
                'original_message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ] : null
        ];
    }

    /**
     * Registrar error en log
     */
    public function logError(string $message, string $level = 'error', array $context = []): void
    {
        switch ($level) {
            case 'warning':
                Log::warning($message, $context);
                break;
            case 'info':
                Log::info($message, $context);
                break;
            case 'critical':
                Log::critical($message, $context);
                break;
            default:
                Log::error($message, $context);
        }
    }

    /**
     * Validar datos y retornar errores
     */
    public function validate(array $data, array $rules): array
    {
        try {
            $validator = \Validator::make($data, $rules);

            if ($validator->fails()) {
                return [
                    'valid' => false,
                    'errors' => $validator->errors()->toArray()
                ];
            }

            return [
                'valid' => true,
                'errors' => []
            ];

        } catch (Exception $e) {
            $this->logError('Error en validación: ' . $e->getMessage());
            
            return [
                'valid' => false,
                'errors' => ['validation' => 'Error al validar los datos']
            ];
        }
    }

    /**
     * Obtener mensaje de error amigable para el usuario
     */
    public function getUserFriendlyMessage(Exception $exception): string
    {
        // Mensajes específicos según el tipo de excepción
        if ($exception instanceof ValidationException) {
            return 'Los datos ingresados no son válidos. Por favor, verifica e intenta nuevamente.';
        }

        if ($exception instanceof QueryException) {
            // Detectar errores comunes de base de datos
            if (str_contains($exception->getMessage(), 'Duplicate entry')) {
                return 'El registro ya existe en el sistema.';
            }
            if (str_contains($exception->getMessage(), 'foreign key constraint')) {
                return 'No se puede eliminar este registro porque está siendo utilizado.';
            }
            return 'Error al acceder a la base de datos. Por favor, intenta más tarde.';
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return 'Debes iniciar sesión para acceder a esta página.';
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return 'La página o recurso solicitado no existe.';
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            $statusCode = $exception->getStatusCode();
            switch ($statusCode) {
                case 403:
                    return 'No tienes permiso para realizar esta acción.';
                case 404:
                    return 'El recurso solicitado no fue encontrado.';
                case 500:
                    return 'Error interno del servidor. Por favor, contacta al administrador.';
                default:
                    return 'Ha ocurrido un error. Por favor, intenta nuevamente.';
            }
        }

        // Mensaje genérico para errores desconocidos
        return 'Ha ocurrido un error inesperado. Por favor, intenta nuevamente o contacta al administrador.';
    }

    /**
     * Determinar si un error es crítico
     */
    public function isCriticalError(Exception $exception): bool
    {
        // Errores críticos que requieren atención inmediata
        $criticalExceptions = [
            \PDOException::class,
            \Illuminate\Database\QueryException::class,
        ];

        foreach ($criticalExceptions as $criticalException) {
            if ($exception instanceof $criticalException) {
                return true;
            }
        }

        // Errores 500 son críticos
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return $exception->getStatusCode() >= 500;
        }

        return false;
    }

    /**
     * Enviar notificación de error crítico
     */
    public function notifyCriticalError(Exception $exception, array $context = []): void
    {
        // Registrar como crítico en log
        $this->logError(
            'ERROR CRÍTICO: ' . $exception->getMessage(),
            'critical',
            array_merge($context, [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'stack_trace' => $exception->getTraceAsString()
            ])
        );

        // Aquí se puede implementar envío de email, Slack, etc.
        // Mail::to(config('mail.admin'))->send(new CriticalErrorMail($exception));
        
        // Registrar en sistema de monitoreo (ej: Sentry, Bugsnag)
        // if (app()->bound('sentry')) {
        //     app('sentry')->captureException($exception);
        // }
    }

    /**
     * Obtener stack trace formateado
     */
    public function getFormattedStackTrace(Exception $exception): array
    {
        $trace = $exception->getTrace();
        $formattedTrace = [];

        foreach ($trace as $index => $item) {
            $formattedTrace[] = [
                'index' => $index,
                'file' => $item['file'] ?? 'unknown',
                'line' => $item['line'] ?? 0,
                'function' => $item['function'] ?? 'unknown',
                'class' => $item['class'] ?? null,
                'type' => $item['type'] ?? null,
            ];
        }

        return $formattedTrace;
    }

    /**
     * Obtener código de error según el tipo de excepción
     */
    protected function getErrorCode(Exception $exception): int
    {
        if ($exception instanceof ValidationException) {
            return self::ERROR_VALIDATION;
        }

        if ($exception instanceof QueryException) {
            return self::ERROR_DATABASE;
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return self::ERROR_AUTHENTICATION;
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            if ($exception->getStatusCode() === 403) {
                return self::ERROR_AUTHORIZATION;
            }
            if ($exception->getStatusCode() === 404) {
                return self::ERROR_NOT_FOUND;
            }
        }

        return self::ERROR_UNKNOWN;
    }
}
