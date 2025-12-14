<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanEstudios extends Model
{
    use HasFactory;

    protected $table = 'plan_estudios';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'duracion_semestres',
        'creditos_totales',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function materias()
    {
        return $this->belongsToMany(
            Materia::class,
            'materia_plan_estudios',
            'plan_estudios_id',
            'materia_id'
        )->withPivot('semestre', 'es_obligatoria')
         ->withTimestamps();
    }
    
    public function materiasAsignadas()
{
    return $this->belongsToMany(
        Materia::class,
        'materia_plan_estudios',
        'plan_estudios_id',
        'materia_id'
    )->withPivot('semestre', 'es_obligatoria')
     ->withTimestamps();
}

    public function materiasOrdenadas()
    {
        return $this->materias()
            ->orderBy('materia_plan_estudios.semestre');
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'plan_estudios_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
