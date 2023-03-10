<?php

namespace App\Services;

use App\Repositories\ProducerRepository;

/**
 *
 */
class ProducerService
{
    /**
     * @var ProducerRepository
     */
    private ProducerRepository $producerRepository;

    /**
     * @param ProducerRepository $producerRepository
     */
    public function __construct(ProducerRepository $producerRepository)
    {
        $this->producerRepository = $producerRepository;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): mixed
    {
        return $this->producerRepository->store($data);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function show(int $id): mixed
    {
        return $this->producerRepository->show($id);
    }

    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id): mixed
    {
        return $this->producerRepository->update($data, $id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        return $this->producerRepository->destroy($id);
    }
}
