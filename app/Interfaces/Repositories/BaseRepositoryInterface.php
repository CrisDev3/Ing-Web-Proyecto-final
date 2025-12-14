<?php

namespace App\Interfaces\Repositories;

/**
 * Interface BaseRepositoryInterface
 * 
 * Interface base para todos los repositorios del sistema
 * Requisito 3: Estructura ordenada de Modelos (Conexiones, Clases que integren operaciones en tablas)
 * 
 * Esta interface define las operaciones CRUD básicas que todos los repositorios deben implementar
 */
interface BaseRepositoryInterface
{
    /**
     * Obtener todos los registros
     * 
     * @param array $columns Columnas a seleccionar
     * @param array $relations Relaciones a cargar (eager loading)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(array $columns = ['*'], array $relations = []);

    /**
     * Obtener un registro por ID
     * 
     * @param int $id
     * @param array $relations Relaciones a cargar
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id, array $relations = []);

    /**
     * Obtener un registro por ID o lanzar excepción
     * 
     * @param int $id
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id, array $relations = []);

    /**
     * Obtener registros con paginación
     * 
     * @param int $perPage Cantidad de registros por página
     * @param array $columns Columnas a seleccionar
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*']);

    /**
     * Crear un nuevo registro
     * 
     * @param array $data Datos del registro
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Actualizar un registro existente
     * 
     * @param int $id ID del registro
     * @param array $data Datos a actualizar
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Eliminar un registro
     * 
     * @param int $id ID del registro
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Buscar registros por criterios
     * 
     * @param array $criteria Criterios de búsqueda ['campo' => 'valor']
     * @param array $relations Relaciones a cargar
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findBy(array $criteria, array $relations = []);

    /**
     * Buscar un solo registro por criterios
     * 
     * @param array $criteria
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findOneBy(array $criteria, array $relations = []);

    /**
     * Contar registros
     * 
     * @param array $criteria Criterios opcionales
     * @return int
     */
    public function count(array $criteria = []): int;

    /**
     * Verificar si existe un registro
     * 
     * @param array $criteria
     * @return bool
     */
    public function exists(array $criteria): bool;

    /**
     * Obtener el primer registro
     * 
     * @param array $criteria
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function first(array $criteria = []);

    /**
     * Crear múltiples registros
     * 
     * @param array $data Array de arrays con datos
     * @return bool
     */
    public function createMultiple(array $data): bool;

    /**
     * Actualizar múltiples registros
     * 
     * @param array $criteria Criterios de filtro
     * @param array $data Datos a actualizar
     * @return int Cantidad de registros actualizados
     */
    public function updateMultiple(array $criteria, array $data): int;

    /**
     * Eliminar múltiples registros
     * 
     * @param array $criteria Criterios de filtro
     * @return int Cantidad de registros eliminados
     */
    public function deleteMultiple(array $criteria): int;
}
