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


    /**
     * @return string
     */
    public function messageErrorNotFound(): string
    {
        return 'Cliente não encontrado';
    }

    /**
     * @param string $col
     * @param string $order
     * @param int $offset
     * @param $searchTerm
     * @return mixed
     */
    public function customIndex(string $col, string $order, int $offset, $searchTerm): mixed
    {
        return $this->model()::when($searchTerm, function ($query, $searchTerm) {
            $query->where('client_name', 'LIKE', '%' . $searchTerm . '%');
        })->orderBy($col, $order)->paginate($offset);
    }
}
