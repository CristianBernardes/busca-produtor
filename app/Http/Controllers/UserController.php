<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Traits\CustomResponseTrait;
use Illuminate\Http\JsonResponse;

/**
 *
 */
class UserController extends Controller
{
    use CustomResponseTrait;

    private UserService $service;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function create(UserRequest $request)
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
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UserRequest $request)
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
