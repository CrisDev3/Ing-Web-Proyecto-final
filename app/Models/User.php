<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'rol',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    /**
     * Roles disponibles en el sistema
     */
    const ROL_ADMINISTRADOR = 'administrador';
    const ROL_DOCENTE = 'docente';
    const ROL_ESTUDIANTE = 'estudiante';

    /**
     * Relación con Estudiante (1:1)
     */
    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'usuario_id');
    }

    /**
     * Relación con Docente (1:1)
     */
    public function docente()
    {
        return $this->hasOne(Docente::class, 'usuario_id');
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdministrador(): bool
    {
        return $this->rol === self::ROL_ADMINISTRADOR;
    }

    /**
     * Verificar si el usuario es docente
     */
    public function esDocente(): bool
    {
        return $this->rol === self::ROL_DOCENTE;
    }

    /**
     * Verificar si el usuario es estudiante
     */
    public function esEstudiante(): bool
    {
        return $this->rol === self::ROL_ESTUDIANTE;
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function tienePermiso(string $permiso): bool
    {
        // Administrador tiene todos los permisos
        if ($this->esAdministrador()) {
            return true;
        }

        // Lógica de permisos específicos por rol
        $permisos = [
            self::ROL_DOCENTE => [
                'ver_grupos',
                'ver_estudiantes',
                'registrar_notas',
            ],
            self::ROL_ESTUDIANTE => [
                'ver_horario',
                'ver_materias',
                'consultar_notas',
            ],
        ];

        return in_array($permiso, $permisos[$this->rol] ?? []);
    }

    /**
     * Scope para filtrar usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por rol
     */
    public function scopePorRol($query, string $rol)
    {
        return $query->where('rol', $rol);
    }
}
