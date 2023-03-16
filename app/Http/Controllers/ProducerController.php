<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProducerRequest;
use Illuminate\Http\Request;
use App\Traits\CustomResponseTrait;
use App\Services\ProducerService;
use Auth;
use Illuminate\Http\JsonResponse;

/**
 *
 */
class ProducerController extends Controller
{
    use CustomResponseTrait;

    /**
     * @var ProducerService
     */
    private ProducerService $service;

    /**
     * @param ProducerService $producerService
     */
    public function __construct(ProducerService $producerService)
    {
        $this->service = $producerService;
    }

    public function index(Request $request)
    {
        $minDistance = $request->input('min_distance') ?? null;
        $maxDistance = $request->input('max_distance') ?? null;

        try {

            return $this->customJsonResponse('Dados carregados com sucesso!', [$this->service->searchProducersByLocation(Auth::user(), $minDistance, $maxDistance)]);
        } catch (\Exception $e) {

            $checkStatusCodeError = $this->checkStatusCodeError($e);

            return $this->customJsonResponse($e->getMessage(), [], $checkStatusCodeError['status_code'], $checkStatusCodeError['message']);
        }
    }

    /**
     * @param ProducerRequest $request
     * @return JsonResponse
     */
    public function create(ProducerRequest $request)
    {
        try {
            return $this->customJsonResponse('Dados carregados com sucesso!', [$this->service->create($request->all())]);
        } catch (\Exception $e) {

            $checkStatusCodeError = $this->checkStatusCodeError($e);

            return $this->customJsonResponse($e->getMessage(), [], $checkStatusCodeError['status_code'], $checkStatusCodeError['message']);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        try {
            return $this->customJsonResponse('Dados carregados com sucesso!', [$this->service->show($id)]);
        } catch (\Exception $e) {

            $checkStatusCodeError = $this->checkStatusCodeError($e);

            return $this->customJsonResponse($e->getMessage(), [], $checkStatusCodeError['status_code'], $checkStatusCodeError['message']);
        }
    }

    /**
     * @param int $id
     * @param ProducerRequest $request
     * @return JsonResponse
     */
    public function update(int $id, ProducerRequest $request)
    {
        try {

            return $this->customJsonResponse('Dados carregados com sucesso!', [$this->service->update($request->all(), $id)]);
        } catch (\Exception $e) {

            $checkStatusCodeError = $this->checkStatusCodeError($e);

            return $this->customJsonResponse($e->getMessage(), [], $checkStatusCodeError['status_code'], $checkStatusCodeError['message']);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            return $this->customJsonResponse('Dados carregados com sucesso!', [$this->service->destroy($id)]);
        } catch (\Exception $e) {

            $checkStatusCodeError = $this->checkStatusCodeError($e);

            return $this->customJsonResponse($e->getMessage(), [], $checkStatusCodeError['status_code'], $checkStatusCodeError['message']);
        }
    }
}
