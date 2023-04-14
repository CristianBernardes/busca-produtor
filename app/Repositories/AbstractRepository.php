<?php

namespace App\Repositories;

use Exception;
use Illuminate\Http\Response;

/**
 *
 */
abstract class AbstractRepository
{
    /**
     * @return mixed
     */
    abstract public function model(): mixed;

    /**
     * @return string
     */
    abstract public function messageErrorNotFound(): string;

    /**
     * @return mixed
     */
    public function index(): mixed
    {
        return $this->model()::paginate(15);
    }

    /**
     * store
     *
     * @param  array $data
     * @return mixed
     */
    public function store(array $data): mixed
    {
        return $this->model()::create($data);
    }

    /**
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function show($id): mixed
    {
        $objectModel = $this->model()::find($id);

        if (!$objectModel) {
            throw new Exception($this->messageErrorNotFound(), Response::HTTP_NOT_FOUND);
        }

        return $objectModel;
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update($id, array $data): mixed
    {
        $objectModel = $this->model()::find($id);

        if (!$objectModel) {
            throw new Exception($this->messageErrorNotFound(), Response::HTTP_NOT_FOUND);
        }

        $update =  $objectModel->update($data);

        if ($update) {

            return $this->model()::find($id);
        } else {
            throw new Exception('Não foi possível atualizar os dados.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }


    /**
     * @param $id
     * @return string
     * @throws Exception
     */
    public function destroy($id): string
    {
        $objectModel = $this->model()::find($id);

        if (!$objectModel) {
            throw new Exception($this->messageErrorNotFound(), Response::HTTP_NOT_FOUND);
        }

        $delete =  $objectModel->delete();

        if ($delete) {

            return 'Dado deletado com sucesso';
        } else {
            throw new Exception('Não foi possível atualizar os dados.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
