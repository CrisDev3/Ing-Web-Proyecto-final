<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';

    protected $fillable = [
        'codigo',
        'materia_id',
        'docente_id',
        'periodo_academico',
        'cupo_maximo',
        'cupo_actual',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id');
    }

    public function horarios()
    {
        return $this->belongsToMany(
            Horario::class,
            'grupo_horarios',
            'grupo_id',
            'horario_id'
        );
    }

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'grupo_id');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(
            Estudiante::class,
            'matriculas',
            'grupo_id',
            'estudiante_id'
        )->withPivot('fecha_matricula', 'estado', 'nota_final');
    }

    /**
     * Verificar si hay cupo disponible
     */
    public function tieneCupo(): bool
    {
        return $this->cupo_actual < $this->cupo_maximo;
    }

    /**
     * Incrementar cupo actual
     */
    public function incrementarCupo(): void
    {
        if ($this->tieneCupo()) {
            $this->increment('cupo_actual');
        }
    }

    /**
     * Decrementar cupo actual
     */
    public function decrementarCupo(): void
    {
        if ($this->cupo_actual > 0) {
            $this->decrement('cupo_actual');
        }
    }

    /**
     * Obtener cupos disponibles
     */
    public function getCuposDisponiblesAttribute(): int
    {
        return $this->cupo_maximo - $this->cupo_actual;
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorPeriodo($query, string $periodo)
    {
        return $query->where('periodo_academico', $periodo);
    }

    public function scopeConCupo($query)
    {
        return $query->whereColumn('cupo_actual', '<', 'cupo_maximo');
    }
}