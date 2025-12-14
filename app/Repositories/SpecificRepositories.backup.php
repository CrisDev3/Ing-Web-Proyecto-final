<?php

namespace App\Repositories;

use App\Models\Estudiante;
use App\Models\Docente;
use App\Models\Matricula;

/**
 * Class EstudianteRepository
 * 
 * Repositorio específico para el modelo Estudiante
 * Requisito 3: Clases que integren las operaciones en las tablas
 */
class EstudianteRepository extends BaseRepository
{
    /**
     * EstudianteRepository constructor.
     */
    public function __construct(Estudiante $model)
    {
        parent::__construct($model);
    }

    /**
     * Buscar estudiantes con sus relaciones principales
     */
    public function getAllWithRelations()
    {
        return $this->all(['*'], ['planEstudios', 'usuario', 'matriculas']);
    }

    /**
     * Buscar estudiantes por plan de estudios
     */
    public function findByPlanEstudios(int $planId)
    {
        return $this->findBy(['plan_estudios_id' => $planId]);
    }

    /**
     * Buscar estudiantes activos
     */
    public function findActivos()
    {
        return $this->findBy(['activo' => true]);
    }

    /**
     * Buscar estudiante por cédula
     */
    public function findByCedula(string $cedula)
    {
        return $this->findOneBy(['cedula' => $cedula]);
    }

    /**
     * Buscar estudiante por email
     */
    public function findByEmail(string $email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Obtener estudiantes con matrículas activas
     */
    public function getConMatriculasActivas()
    {
        return $this->model
            ->whereHas('matriculas', function ($query) {
                $query->where('estado', 'activa');
            })
            ->with(['matriculas' => function ($query) {
                $query->where('estado', 'activa');
            }])
            ->get();
    }

    /**
     * Buscar estudiantes con filtros avanzados
     */
    public function searchWithFilters(array $filters)
    {
        $query = $this->model->newQuery();

        // Búsqueda por texto
        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por plan
        if (isset($filters['plan_id']) && $filters['plan_id']) {
            $query->where('plan_estudios_id', $filters['plan_id']);
        }

        // Filtro por estado
        if (isset($filters['activo']) && $filters['activo'] !== '') {
            $query->where('activo', $filters['activo']);
        }

        return $query->with(['planEstudios', 'usuario'])
                     ->orderBy('apellido')
                     ->orderBy('nombre')
                     ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Obtener estadísticas de estudiantes
     */
    public function getEstadisticas(): array
    {
        return [
            'total' => $this->count(),
            'activos' => $this->count(['activo' => true]),
            'inactivos' => $this->count(['activo' => false]),
            'con_matriculas_activas' => $this->model
                ->whereHas('matriculas', function ($query) {
                    $query->where('estado', 'activa');
                })
                ->count(),
        ];
    }
}

/**
 * Class DocenteRepository
 * 
 * Repositorio específico para el modelo Docente
 */
class DocenteRepository extends BaseRepository
{
    /**
     * DocenteRepository constructor.
     */
    public function __construct(Docente $model)
    {
        parent::__construct($model);
    }

    /**
     * Obtener todos los docentes con sus relaciones
     */
    public function getAllWithRelations()
    {
        return $this->all(['*'], ['usuario', 'grupos']);
    }

    /**
     * Buscar docentes activos
     */
    public function findActivos()
    {
        return $this->findBy(['activo' => true]);
    }

    /**
     * Buscar docente por cédula
     */
    public function findByCedula(string $cedula)
    {
        return $this->findOneBy(['cedula' => $cedula]);
    }

    /**
     * Buscar docente por email
     */
    public function findByEmail(string $email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Buscar docentes por especialidad
     */
    public function findByEspecialidad(string $especialidad)
    {
        return $this->model
            ->where('especialidad', 'like', "%{$especialidad}%")
            ->where('activo', true)
            ->get();
    }

    /**
     * Obtener docentes con grupos activos
     */
    public function getConGruposActivos()
    {
        return $this->model
            ->whereHas('grupos', function ($query) {
                $query->where('activo', true);
            })
            ->with(['grupos' => function ($query) {
                $query->where('activo', true)
                      ->with(['materia', 'horarios']);
            }])
            ->get();
    }

    /**
     * Verificar disponibilidad de docente en horario
     */
    public function estaDisponibleEnHorario(int $docenteId, int $horarioId, ?int $grupoExcluidoId = null): bool
    {
        $docente = $this->find($docenteId, ['grupos.horarios']);
        
        if (!$docente) {
            return false;
        }

        foreach ($docente->grupos as $grupo) {
            if ($grupoExcluidoId && $grupo->id === $grupoExcluidoId) {
                continue;
            }

            foreach ($grupo->horarios as $horario) {
                if ($horario->id === $horarioId) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Obtener carga horaria del docente
     */
    public function getCargaHoraria(int $docenteId): array
    {
        $docente = $this->find($docenteId, ['grupos.materia', 'grupos.horarios']);
        
        if (!$docente) {
            return [
                'grupos' => 0,
                'materias' => 0,
                'horas_totales' => 0,
            ];
        }

        $gruposActivos = $docente->grupos->where('activo', true);
        
        return [
            'grupos' => $gruposActivos->count(),
            'materias' => $gruposActivos->unique('materia_id')->count(),
            'horas_totales' => $gruposActivos->sum(function ($grupo) {
                return $grupo->materia->total_horas ?? 0;
            }),
        ];
    }

    /**
     * Buscar docentes con filtros
     */
    public function searchWithFilters(array $filters)
    {
        $query = $this->model->newQuery();

        // Búsqueda por texto
        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('especialidad', 'like', "%{$search}%");
            });
        }

        // Filtro por estado
        if (isset($filters['activo']) && $filters['activo'] !== '') {
            $query->where('activo', $filters['activo']);
        }

        // Filtro por especialidad
        if (isset($filters['especialidad']) && $filters['especialidad']) {
            $query->where('especialidad', 'like', "%{$filters['especialidad']}%");
        }

        return $query->with(['usuario', 'grupos'])
                     ->orderBy('apellido')
                     ->orderBy('nombre')
                     ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Obtener estadísticas de docentes
     */
    public function getEstadisticas(): array
    {
        return [
            'total' => $this->count(),
            'activos' => $this->count(['activo' => true]),
            'inactivos' => $this->count(['activo' => false]),
            'con_grupos_activos' => $this->model
                ->whereHas('grupos', function ($query) {
                    $query->where('activo', true);
                })
                ->count(),
        ];
    }
}

/**
 * Class MatriculaRepository
 * 
 * Repositorio específico para el modelo Matricula
 */
class MatriculaRepository extends BaseRepository
{
    /**
     * MatriculaRepository constructor.
     */
    public function __construct(Matricula $model)
    {
        parent::__construct($model);
    }

    /**
     * Obtener matrículas activas
     */
    public function getActivas()
    {
        return $this->findBy(['estado' => 'activa'], ['estudiante', 'grupo.materia', 'grupo.docente']);
    }

    /**
     * Obtener matrículas por estudiante
     */
    public function getByEstudiante(int $estudianteId)
    {
        return $this->findBy(['estudiante_id' => $estudianteId], ['grupo.materia', 'grupo.docente', 'grupo.horarios']);
    }

    /**
     * Obtener matrículas por grupo
     */
    public function getByGrupo(int $grupoId)
    {
        return $this->findBy(['grupo_id' => $grupoId], ['estudiante']);
    }

    /**
     * Verificar si estudiante está matriculado en grupo
     */
    public function estaMatriculado(int $estudianteId, int $grupoId): bool
    {
        return $this->exists([
            'estudiante_id' => $estudianteId,
            'grupo_id' => $grupoId,
            'estado' => 'activa'
        ]);
    }

    /**
     * Obtener matrículas por período académico
     */
    public function getByPeriodo(string $periodo)
    {
        return $this->model
            ->whereHas('grupo', function ($query) use ($periodo) {
                $query->where('periodo_academico', $periodo);
            })
            ->with(['estudiante', 'grupo.materia'])
            ->get();
    }

    /**
     * Obtener estadísticas de matrículas
     */
    public function getEstadisticas(): array
    {
        return [
            'total' => $this->count(),
            'activas' => $this->count(['estado' => 'activa']),
            'aprobadas' => $this->count(['estado' => 'aprobada']),
            'reprobadas' => $this->count(['estado' => 'reprobada']),
            'canceladas' => $this->count(['estado' => 'cancelada']),
        ];
    }

    /**
     * Obtener promedio general de notas
     */
    public function getPromedioGeneral(): float
    {
        return $this->model
            ->whereIn('estado', ['aprobada', 'reprobada'])
            ->whereNotNull('nota_final')
            ->avg('nota_final') ?? 0.0;
    }
}
