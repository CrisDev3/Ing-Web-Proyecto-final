<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    protected $fillable = [
        'usuario_id',
        'nombre',
        'apellido',
        'cedula',
        'email',
        'telefono',
        'direccion',
        'plan_estudios_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación con Usuario (N:1)
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con Plan de Estudios (N:1)
     */
    public function planEstudios()
    {
        return $this->belongsTo(PlanEstudios::class, 'plan_estudios_id');
    }

    /**
     * Relación con Matrículas (1:N)
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'estudiante_id');
    }

    /**
     * Relación con Grupos a través de Matrículas (N:M)
     */
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'matriculas', 'estudiante_id', 'grupo_id')
                    ->withPivot('fecha_matricula', 'estado', 'nota_final')
                    ->withTimestamps();
    }

    /**
     * Obtener nombre completo del estudiante
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    /**
     * Obtener matrículas activas del estudiante
     */
    public function matriculasActivas()
    {
        return $this->matriculas()->where('estado', 'activa');
    }

    /**
     * Verificar si el estudiante puede matricularse en una materia
     * (validando prerequisitos)
     */
    public function puedeMatricularse(Materia $materia): bool
    {
        // Obtener prerequisitos de la materia
        $prerequisitos = $materia->prerequisitos;

        if ($prerequisitos->isEmpty()) {
            return true;
        }

        // Verificar que todos los prerequisitos estén aprobados
        foreach ($prerequisitos as $prerequisito) {
            $aprobado = $this->matriculas()
                ->whereHas('grupo', function ($query) use ($prerequisito) {
                    $query->where('materia_id', $prerequisito->id);
                })
                ->where('estado', 'aprobada')
                ->exists();

            if (!$aprobado) {
                return false;
            }
        }

        return true;
    }

    /**
     * Obtener horario del estudiante (matrículas activas)
     */
    public function obtenerHorario()
    {
        return $this->matriculasActivas()
            ->with(['grupo.horarios', 'grupo.materia', 'grupo.docente'])
            ->get()
            ->map(function ($matricula) {
                return [
                    'materia' => $matricula->grupo->materia->nombre,
                    'docente' => $matricula->grupo->docente->nombreCompleto,
                    'horarios' => $matricula->grupo->horarios,
                ];
            });
    }

    /**
     * Calcular carga académica actual (créditos)
     */
    public function cargaAcademica(): int
    {
        return $this->matriculasActivas()
            ->with('grupo.materia')
            ->get()
            ->sum(function ($matricula) {
                return $matricula->grupo->materia->creditos;
            });
    }

    /**
     * Scope para estudiantes activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por plan de estudios
     */
    public function scopePorPlan($query, int $planId)
    {
        return $query->where('plan_estudios_id', $planId);
    }
}
