<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProducerRequest;
use Illuminate\Http\Request;
use App\Repositories\ProducerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProducerController extends Controller
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
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $minDistance = $request->input('min_distance') ?? null;
        $maxDistance = $request->input('max_distance') ?? null;
        $minVolume = $request->input('min_volume') ?? null;
        $maxVolume = $request->input('max_volume') ?? null;
        $city = $request->input('city') ?? null;
        $state = $request->input('state') ?? null;
        $col = $request->input('col') ?? 'distance';
        $order = $request->input('order') ?? 'ASC';
        $offset = $request->input('offset') ?? 15;
        $searchTerm = $request->input('search_term') ?? null;

        try {

            return response()->json($this->producerRepository->searchProducersByLocation(
                Auth::user(),
                $minDistance,
                $maxDistance,
                $minVolume,
                $maxVolume,
                $city,
                $state,
                $col,
                $order,
                $offset,
                $searchTerm
            ));
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }

    /**
     * @param ProducerRequest $request
     * @return JsonResponse
     */
    public function store(ProducerRequest $request): JsonResponse
    {
        try {
            return response()->json($this->producerRepository->store($request->all()));
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

            return response()->json($this->producerRepository->customShow($id, Auth::user()));
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }

    /**
     * @param $id
     * @param ProducerRequest $request
     * @return JsonResponse
     */
    public function update($id, ProducerRequest $request): JsonResponse
    {
        try {

            return response()->json($this->producerRepository->update($id, $request->all()));
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

            return response()->json(['success' => $this->producerRepository->destroy($id)]);
        } catch (\Exception $e) {

            return $this->checkStatusCodeError($e);
        }
    }
}
