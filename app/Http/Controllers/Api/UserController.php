<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Repositories\UserRepository;
use App\Http\Requests\UserRequest;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 *
 */
class UserController extends Controller
{

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $col = $request->input('col') ?? 'name';
        $order = $request->input('order') ?? 'ASC';
        $offset = $request->input('offset') ?? 15;
        $searchTerm = $request->input('search_term') ?? null;
        $profileFilter = $request->input('profile') ?? null;
        $clientName = $request->input('client_name') ?? null;

        try {
            return response()->json($this->userRepository->customIndex($col, $order, $offset, $searchTerm, $profileFilter, $clientName));
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }

    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        try {

            return response()->json($this->userRepository->store($request->all()));
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

            return response()->json($this->userRepository->show($id));
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }

    /**
     * @param $id
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function update($id, UserRequest $request): JsonResponse
    {
        try {

            return response()->json($this->userRepository->update($id, $request->all()));
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

            if ($id == Auth::user()->id) {
                throw new Exception('Não é possivel deletar o usuário logado!', Response::HTTP_UNAUTHORIZED);
            }

            return response()->json(['success' => $this->userRepository->destroy($id)]);
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }
}
