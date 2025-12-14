<?php

namespace App\Repositories;

use App\Interfaces\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 * 
 * Implementación base de repositorio con operaciones CRUD comunes
 * Requisito 3: Estructura ordenada de Modelos - Clase base que integra operaciones en tablas
 * 
 * Esta clase implementa el patrón Repository para separar la lógica
 * de acceso a datos de la lógica de negocio
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     * 
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Obtener todos los registros
     */
    public function all(array $columns = ['*'], array $relations = [])
    {
        $query = $this->model->select($columns);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Obtener un registro por ID
     */
    public function find(int $id, array $relations = [])
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->find($id);
    }

    /**
     * Obtener un registro por ID o lanzar excepción
     */
    public function findOrFail(int $id, array $relations = [])
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->findOrFail($id);
    }

    /**
     * Obtener registros con paginación
     */
    public function paginate(int $perPage = 15, array $columns = ['*'])
    {
        return $this->model->select($columns)->paginate($perPage);
    }

    /**
     * Crear un nuevo registro
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Actualizar un registro existente
     */
    public function update(int $id, array $data): bool
    {
        $record = $this->find($id);
        
        if (!$record) {
            return false;
        }

        return $record->update($data);
    }

    /**
     * Eliminar un registro
     */
    public function delete(int $id): bool
    {
        $record = $this->find($id);
        
        if (!$record) {
            return false;
        }

        return $record->delete();
    }

    /**
     * Buscar registros por criterios
     */
    public function findBy(array $criteria, array $relations = [])
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                // Para búsquedas con operadores: ['field' => ['operator', 'value']]
                $query->where($field, $value[0], $value[1]);
            } else {
                $query->where($field, '=', $value);
            }
        }

        return $query->get();
    }

    /**
     * Buscar un solo registro por criterios
     */
    public function findOneBy(array $criteria, array $relations = [])
    {
        $query = $this->model->newQuery();

        if (!empty($relations)) {
            $query->with($relations);
        }

        foreach ($criteria as $field => $value) {
            $query->where($field, '=', $value);
        }

        return $query->first();
    }

    /**
     * Contar registros
     */
    public function count(array $criteria = []): int
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, '=', $value);
        }

        return $query->count();
    }

    /**
     * Verificar si existe un registro
     */
    public function exists(array $criteria): bool
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, '=', $value);
        }

        return $query->exists();
    }

    /**
     * Obtener el primer registro
     */
    public function first(array $criteria = [])
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, '=', $value);
        }

        return $query->first();
    }

    /**
     * Crear múltiples registros
     */
    public function createMultiple(array $data): bool
    {
        return $this->model->insert($data);
    }

    /**
     * Actualizar múltiples registros
     */
    public function updateMultiple(array $criteria, array $data): int
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, '=', $value);
        }

        return $query->update($data);
    }

    /**
     * Eliminar múltiples registros
     */
    public function deleteMultiple(array $criteria): int
    {
        $query = $this->model->newQuery();

        foreach ($criteria as $field => $value) {
            $query->where($field, '=', $value);
        }

        return $query->delete();
    }

    /**
     * Iniciar una transacción de base de datos
     */
    protected function beginTransaction(): void
    {
        \DB::beginTransaction();
    }

    /**
     * Confirmar una transacción de base de datos
     */
    protected function commit(): void
    {
        \DB::commit();
    }

    /**
     * Revertir una transacción de base de datos
     */
    protected function rollback(): void
    {
        \DB::rollBack();
    }

    /**
     * Obtener instancia fresca del modelo
     */
    protected function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Establecer nuevo modelo
     */
    protected function setModel(Model $model): void
    {
        $this->model = $model;
    }

    /**
     * Aplicar filtros dinámicos al query
     * 
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyFilters(array $filters)
    {
        $query = $this->model->newQuery();

        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                if (is_array($value) && isset($value['operator'])) {
                    $query->where($field, $value['operator'], $value['value']);
                } elseif (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, '=', $value);
                }
            }
        }

        return $query;
    }
}
