<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Services\ClientService;
use App\Traits\CustomResponseTrait;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    use CustomResponseTrait;

    /**
     * @var ClientService
     */
    private ClientService $service;

    /**
     * @param ClientService $clientService
     */
    public function __construct(ClientService $clientService)
    {
        $this->service = $clientService;
    }
    /**
     * @param ClientRequest $request
     * @return JsonResponse
     */
    public function create(ClientRequest $request)
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
     * @param ClientRequest $request
     * @return JsonResponse
     */
    public function update(int $id, ClientRequest $request)
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
