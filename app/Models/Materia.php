<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'creditos',
        'horas_teoricas',
        'horas_practicas',
    ];

    public function planesEstudios()
    {
        return $this->belongsToMany(PlanEstudios::class, 'materia_plan_estudios', 'materia_id', 'plan_estudios_id')
            ->withPivot('semestre', 'es_obligatoria')
            ->withTimestamps();
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'materia_id');
    }

    /**
     * Prerequisitos que requiere esta materia
     */
    public function prerequisitos()
    {
        return $this->belongsToMany(
            Materia::class,
            'prerequisitos',
            'materia_id',
            'prerequisito_id'
        );
    }

    /**
     * Materias que tienen a esta como prerequisito
     */
    public function dependientes()
    {
        return $this->belongsToMany(
            Materia::class,
            'prerequisitos',
            'prerequisito_id',
            'materia_id'
        );
    }

    public function getTotalHorasAttribute(): int
    {
        return $this->horas_teoricas + $this->horas_practicas;
    }
}