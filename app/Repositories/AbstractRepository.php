<?php

namespace App\Repositories;

use App\Contracts\AbstractInterface;

/**
 *
 */
abstract class AbstractRepository implements AbstractInterface
{
    /**
     * @return mixed
     */
    abstract public function model(): mixed;

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->model()::all();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data): mixed
    {
        return $this->model()::create($data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function show(int $id): mixed
    {
        return $this->model()::findOrFail($id);
    }

    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id): mixed
    {
        $model = $this->model()::findOrFail($id);

        $model->update($data);

        return $model;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $model = $this->model()::findOrFail($id);

        $delete = $model->delete();

        if ($delete) {

            return true;
        }

        return false;
    }
}
