<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find user by id (with specific return type)
     */
    public function findById(int $id): ?User;
}
