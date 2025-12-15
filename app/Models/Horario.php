<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'dia',
        'hora_inicio',
        'hora_fin',
        'tipo',
    ];

    protected $casts = [
        'hora_inicio' => 'string',
        'hora_fin' => 'string',
    ];

    const DIAS = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const TIPOS = ['teorico', 'practico', 'laboratorio'];

    public function grupos()
    {
        return $this->belongsToMany(
            Grupo::class,
            'grupo_horarios',
            'horario_id',
            'grupo_id'
        );
    }

    /**
     * Verificar si este horario se solapa con otro
     */
    public function solapaCon(Horario $otro): bool
    {
        if ($this->dia !== $otro->dia) {
            return false;
        }

        $inicio1 = strtotime($this->hora_inicio);
        $fin1 = strtotime($this->hora_fin);
        $inicio2 = strtotime($otro->hora_inicio);
        $fin2 = strtotime($otro->hora_fin);

        return ($inicio1 < $fin2) && ($inicio2 < $fin1);
    }

    public function scopePorDia($query, string $dia)
    {
        return $query->where('dia', $dia);
    }
}