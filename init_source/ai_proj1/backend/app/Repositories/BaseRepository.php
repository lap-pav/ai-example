<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Find model by id
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find model by id or fail
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get all models
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Get paginated models
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Create a new model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a model
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model->fresh();
    }

    /**
     * Delete a model
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Find model by specific field
     */
    public function findBy(string $field, mixed $value): ?Model
    {
        return $this->model->where($field, $value)->first();
    }

    /**
     * Find models where field equals value
     */
    public function where(string $field, mixed $value): Collection
    {
        return $this->model->where($field, $value)->get();
    }

    /**
     * Find models with multiple conditions
     */
    public function whereMultiple(array $conditions): Collection
    {
        $query = $this->model->newQuery();
        
        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }
        
        return $query->get();
    }

    /**
     * Count total models
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Check if model exists
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }
}
