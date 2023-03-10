<?php

namespace App\Repositories;

use App\Models\Client;

/**
 *
 */
class ClientRepository extends AbstractRepository
{

    /**
     * @return mixed
     */
    public function model(): mixed
    {
        return Client::class;
    }
}
