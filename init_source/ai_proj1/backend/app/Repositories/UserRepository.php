<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = $this->findBy('email', $email);
        return $user;
    }

    /**
     * Find user by id (with specific return type)
     */
    public function findById(int $id): ?User
    {
        /** @var User|null $user */
        $user = $this->find($id);
        return $user;
    }

    /**
     * Create a new user with password hashing
     */
    public function create(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        /** @var User $user */
        $user = parent::create($data);
        return $user;
    }

    /**
     * Update user with password hashing if needed
     */
    public function update($model, array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        /** @var User $user */
        $user = parent::update($model, $data);
        return $user;
    }
}
