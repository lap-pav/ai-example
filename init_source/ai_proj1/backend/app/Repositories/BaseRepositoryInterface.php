<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    /**
     * Find model by id
     */
    public function find(int $id): ?Model;

    /**
     * Find model by id or fail
     */
    public function findOrFail(int $id): Model;

    /**
     * Get all models
     */
    public function all(): Collection;

    /**
     * Get paginated models
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new model
     */
    public function create(array $data): Model;

    /**
     * Update a model
     */
    public function update(Model $model, array $data): Model;

    /**
     * Delete a model
     */
    public function delete(Model $model): bool;

    /**
     * Find model by specific field
     */
    public function findBy(string $field, mixed $value): ?Model;

    /**
     * Find models where field equals value
     */
    public function where(string $field, mixed $value): Collection;

    /**
     * Find models with multiple conditions
     */
    public function whereMultiple(array $conditions): Collection;

    /**
     * Count total models
     */
    public function count(): int;

    /**
     * Check if model exists
     */
    public function exists(int $id): bool;
}
