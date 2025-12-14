<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas';

    protected $fillable = [
        'estudiante_id',
        'grupo_id',
        'fecha_matricula',
        'estado',
        'nota_final',
    ];

    protected $casts = [
        'fecha_matricula' => 'date',
        'nota_final' => 'decimal:2',
    ];

    const ESTADO_ACTIVA = 'activa';
    const ESTADO_CANCELADA = 'cancelada';
    const ESTADO_APROBADA = 'aprobada';
    const ESTADO_REPROBADA = 'reprobada';

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    /**
     * Verificar si la matrícula está activa
     */
    public function estaActiva(): bool
    {
        return $this->estado === self::ESTADO_ACTIVA;
    }

    /**
     * Cancelar matrícula
     */
    public function cancelar(): void
    {
        $this->estado = self::ESTADO_CANCELADA;
        $this->save();
        
        // Decrementar cupo del grupo
        $this->grupo->decrementarCupo();
    }

    /**
     * Aprobar matrícula
     */
    public function aprobar(float $nota): void
    {
        $this->estado = self::ESTADO_APROBADA;
        $this->nota_final = $nota;
        $this->save();
    }

    /**
     * Reprobar matrícula
     */
    public function reprobar(float $nota): void
    {
        $this->estado = self::ESTADO_REPROBADA;
        $this->nota_final = $nota;
        $this->save();
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVA);
    }

    public function scopePorEstudiante($query, int $estudianteId)
    {
        return $query->where('estudiante_id', $estudianteId);
    }

    public function scopePorGrupo($query, int $grupoId)
    {
        return $query->where('grupo_id', $grupoId);
    }
}