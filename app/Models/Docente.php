<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes';

    protected $fillable = [
        'usuario_id',
        'nombre',
        'apellido',
        'cedula',
        'especialidad',
        'email',
        'telefono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'docente_id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function gruposActivos()
    {
        return $this->grupos()->where('activo', true);
    }

    /**
     * Verificar si el docente está disponible en un horario específico
     */
    public function estaDisponible(Horario $horario, ?int $grupoExcluidoId = null): bool
    {
        $query = $this->grupos()
            ->whereHas('horarios', function ($q) use ($horario) {
                $q->where('horarios.id', $horario->id);
            });

        if ($grupoExcluidoId) {
            $query->where('grupos.id', '!=', $grupoExcluidoId);
        }

        return !$query->exists();
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}