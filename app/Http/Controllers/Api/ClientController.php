<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Repositories\ClientRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 */
class ClientController extends Controller
{
    /**
     * @var ClientRepository
     */
    private ClientRepository $clientRepository;

    /**
     * @param ClientRepository $clientRepository
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $col = $request->input('col') ?? 'client_name';
        $order = $request->input('order') ?? 'ASC';
        $offset = $request->input('offset') ?? 15;
        $searchTerm = $request->input('search_term') ?? null;

        return response()->json($this->clientRepository->customIndex($col, $order, $offset, $searchTerm));
    }

    /**
     * @param ClientRequest $request
     * @return JsonResponse
     */
    public function store(ClientRequest $request): JsonResponse
    {
        try {
            return response()->json($this->clientRepository->store($request->all()));
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {

            return response()->json($this->clientRepository->show($id));
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }

    /**
     * @param $id
     * @param ClientRequest $request
     * @return JsonResponse
     */
    public function update($id, ClientRequest $request): JsonResponse
    {
        try {

            return response()->json($this->clientRepository->update($id, $request->all()));
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {

            return response()->json(['success' => $this->clientRepository->destroy($id)]);
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }
}
