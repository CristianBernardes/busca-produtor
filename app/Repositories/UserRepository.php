<?php

namespace App\Repositories;

use App\Models\User;

/**
 *
 */
class UserRepository extends AbstractRepository
{
    /**
     * @return mixed
     */
    public function model(): mixed
    {
        return User::class;
    }
}
