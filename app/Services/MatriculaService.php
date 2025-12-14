<?php

namespace App\Services;

use App\Models\Estudiante;
use App\Models\Grupo;
use App\Models\Matricula;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class MatriculaService
{
    /**
     * Matricular estudiante en uno o varios grupos
     *
     * @param Estudiante $estudiante
     * @param array $gruposIds
     * @return array
     * @throws \Exception
     */
    public function matricularEstudiante(Estudiante $estudiante, array $gruposIds): array
    {
        DB::beginTransaction();

        try {
            $grupos = Grupo::with(['materia.prerequisitos', 'horarios'])
                          ->findOrFail($gruposIds);

            // Validaciones
            $this->validarMatriculas($estudiante, $grupos);

            $matriculasCreadas = [];

            foreach ($grupos as $grupo) {
                // Verificar cupo nuevamente dentro de la transacción
                $grupo = Grupo::lockForUpdate()->find($grupo->id);

                if (!$grupo->tieneCupo()) {
                    throw new \Exception("El grupo {$grupo->codigo} no tiene cupos disponibles.");
                }

                // Crear matrícula
                $matricula = Matricula::create([
                    'estudiante_id' => $estudiante->id,
                    'grupo_id' => $grupo->id,
                    'fecha_matricula' => now(),
                    'estado' => Matricula::ESTADO_ACTIVA,
                ]);

                // Incrementar cupo del grupo
                $grupo->incrementarCupo();

                $matriculasCreadas[] = $matricula;
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Matrícula realizada exitosamente.',
                'matriculas' => $matriculasCreadas,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Validar que el estudiante puede matricularse en los grupos
     *
     * @param Estudiante $estudiante
     * @param Collection $grupos
     * @throws \Exception
     */
    protected function validarMatriculas(Estudiante $estudiante, Collection $grupos): void
    {
        // 1. Validar prerequisitos
        foreach ($grupos as $grupo) {
            if (!$estudiante->puedeMatricularse($grupo->materia)) {
                throw new \Exception(
                    "No cumple con los prerequisitos para la materia: {$grupo->materia->nombre}"
                );
            }
        }

        // 2. Validar que no esté ya matriculado en la misma materia
        foreach ($grupos as $grupo) {
            $yaMatriculado = $estudiante->matriculasActivas()
                ->whereHas('grupo', function ($query) use ($grupo) {
                    $query->where('materia_id', $grupo->materia_id);
                })
                ->exists();

            if ($yaMatriculado) {
                throw new \Exception(
                    "Ya está matriculado en la materia: {$grupo->materia->nombre}"
                );
            }
        }

        // 3. Validar cupos disponibles
        foreach ($grupos as $grupo) {
            if (!$grupo->tieneCupo()) {
                throw new \Exception(
                    "El grupo {$grupo->codigo} no tiene cupos disponibles."
                );
            }
        }

        // 4. Validar solapamiento de horarios
        $this->validarHorarios($estudiante, $grupos);

        // 5. Validar carga académica máxima (ej: 24 créditos)
        $cargaActual = $estudiante->cargaAcademica();
        $cargaNueva = $grupos->sum(function ($grupo) {
            return $grupo->materia->creditos;
        });

        $CARGA_MAXIMA = 24;
        if (($cargaActual + $cargaNueva) > $CARGA_MAXIMA) {
            throw new \Exception(
                "Excede la carga académica máxima permitida ({$CARGA_MAXIMA} créditos)."
            );
        }
    }

    /**
     * Validar que no haya solapamiento de horarios
     *
     * @param Estudiante $estudiante
     * @param Collection $gruposNuevos
     * @throws \Exception
     */
    protected function validarHorarios(Estudiante $estudiante, Collection $gruposNuevos): void
    {
        // Obtener horarios actuales del estudiante
        $horariosActuales = [];
        $matriculasActivas = $estudiante->matriculasActivas()->with('grupo.horarios')->get();

        foreach ($matriculasActivas as $matricula) {
            foreach ($matricula->grupo->horarios as $horario) {
                $horariosActuales[] = $horario;
            }
        }

        // Verificar solapamiento con horarios nuevos
        foreach ($gruposNuevos as $grupo) {
            foreach ($grupo->horarios as $horarioNuevo) {
                foreach ($horariosActuales as $horarioActual) {
                    if ($horarioNuevo->solapaCon($horarioActual)) {
                        throw new \Exception(
                            "Conflicto de horario: {$grupo->materia->nombre} se solapa con otra materia el día {$horarioNuevo->dia}."
                        );
                    }
                }
            }
        }

        // Verificar solapamiento entre los grupos nuevos
        $horariosNuevos = [];
        foreach ($gruposNuevos as $grupo) {
            foreach ($grupo->horarios as $horario) {
                foreach ($horariosNuevos as $horarioComparar) {
                    if ($horario->solapaCon($horarioComparar)) {
                        throw new \Exception(
                            "Conflicto de horario entre las materias seleccionadas el día {$horario->dia}."
                        );
                    }
                }
                $horariosNuevos[] = $horario;
            }
        }
    }

    /**
     * Cancelar matrícula
     *
     * @param Matricula $matricula
     * @return array
     */
    public function cancelarMatricula(Matricula $matricula): array
    {
        DB::beginTransaction();

        try {
            if (!$matricula->estaActiva()) {
                throw new \Exception('Solo se pueden cancelar matrículas activas.');
            }

            $matricula->cancelar();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Matrícula cancelada exitosamente.',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtener grupos disponibles para un estudiante
     *
     * @param Estudiante $estudiante
     * @param string $periodo
     * @return Collection
     */
    public function obtenerGruposDisponibles(Estudiante $estudiante, string $periodo): Collection
    {
        // Obtener materias del plan de estudios del estudiante
        $materiasDelPlan = $estudiante->planEstudios->materias->pluck('id');

        // Obtener grupos activos del período que:
        // 1. Pertenezcan al plan del estudiante
        // 2. Tengan cupo disponible
        // 3. El estudiante cumpla con prerequisitos
        // 4. No esté ya matriculado

        $grupos = Grupo::with(['materia.prerequisitos', 'docente', 'horarios'])
            ->where('periodo_academico', $periodo)
            ->where('activo', true)
            ->whereIn('materia_id', $materiasDelPlan)
            ->conCupo()
            ->get()
            ->filter(function ($grupo) use ($estudiante) {
                // Filtrar por prerequisitos
                if (!$estudiante->puedeMatricularse($grupo->materia)) {
                    return false;
                }

                // Filtrar si ya está matriculado
                $yaMatriculado = $estudiante->matriculasActivas()
                    ->whereHas('grupo', function ($query) use ($grupo) {
                        $query->where('materia_id', $grupo->materia_id);
                    })
                    ->exists();

                return !$yaMatriculado;
            });

        return $grupos;
    }

    /**
     * Registrar calificaciones
     *
     * @param Matricula $matricula
     * @param float $nota
     * @return array
     */
    public function registrarCalificacion(Matricula $matricula, float $nota): array
    {
        DB::beginTransaction();

        try {
            if (!$matricula->estaActiva()) {
                throw new \Exception('Solo se pueden calificar matrículas activas.');
            }

            if ($nota < 0 || $nota > 100) {
                throw new \Exception('La nota debe estar entre 0 y 100.');
            }

            $NOTA_APROBATORIA = 70;

            if ($nota >= $NOTA_APROBATORIA) {
                $matricula->aprobar($nota);
                $mensaje = 'Materia aprobada.';
            } else {
                $matricula->reprobar($nota);
                $mensaje = 'Materia reprobada.';
            }

            DB::commit();

            return [
                'success' => true,
                'message' => $mensaje,
                'matricula' => $matricula,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generar reporte de matrícula por estudiante
     *
     * @param Estudiante $estudiante
     * @return array
     */
    public function generarReporteEstudiante(Estudiante $estudiante): array
    {
        $matriculas = $estudiante->matriculas()
            ->with(['grupo.materia', 'grupo.docente'])
            ->orderBy('fecha_matricula', 'desc')
            ->get();

        $estadisticas = [
            'total_materias' => $matriculas->count(),
            'activas' => $matriculas->where('estado', Matricula::ESTADO_ACTIVA)->count(),
            'aprobadas' => $matriculas->where('estado', Matricula::ESTADO_APROBADA)->count(),
            'reprobadas' => $matriculas->where('estado', Matricula::ESTADO_REPROBADA)->count(),
            'canceladas' => $matriculas->where('estado', Matricula::ESTADO_CANCELADA)->count(),
            'promedio' => $matriculas
                ->whereIn('estado', [Matricula::ESTADO_APROBADA, Matricula::ESTADO_REPROBADA])
                ->avg('nota_final'),
            'creditos_aprobados' => $matriculas
                ->where('estado', Matricula::ESTADO_APROBADA)
                ->sum(function ($m) {
                    return $m->grupo->materia->creditos;
                }),
        ];

        return [
            'estudiante' => $estudiante,
            'matriculas' => $matriculas,
            'estadisticas' => $estadisticas,
        ];
    }
}
