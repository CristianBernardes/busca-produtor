<?php

namespace App\Repositories;

use App\Models\Producer;

/**
 *
 */
class ProducerRepository extends AbstractRepository
{

    /**
     * @return mixed
     */
    public function model(): mixed
    {
        return Producer::class;
    }
}
