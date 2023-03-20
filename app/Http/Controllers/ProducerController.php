<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProducerRequest;
use Illuminate\Http\Request;
use App\Traits\CustomResponseTrait;
use App\Services\ProducerService;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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

            return response()->json($this->service->searchProducersByLocation(Auth::user(), $minDistance, $maxDistance));
        } catch (\Exception $e) {

            $checkStatusCodeError = $this->checkStatusCodeError($e);

            return response()->json(
                [
                    'error' => $e->getMessage(),
                    'message' => $checkStatusCodeError['message']
                ],
                $checkStatusCodeError['status_code']
            );
        }
    }

    /**
     * @param ProducerRequest $request
     * @return JsonResponse
     */
    public function store(ProducerRequest $request)
    {
        try {

            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            $request->merge([
                'coordinates' => DB::raw("POINT($latitude, $longitude)"),
            ]);

            return response()->json($this->service->create($request->all()));
        } catch (\Exception $e) {

            $checkStatusCodeError = $this->checkStatusCodeError($e);

            return response()->json(
                [
                    'error' => $e->getMessage(),
                    'message' => $checkStatusCodeError['message']
                ],
                $checkStatusCodeError['status_code']
            );
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        try {

            return response()->json($this->service->show($id));
        } catch (\Exception $e) {

            $checkStatusCodeError = $this->checkStatusCodeError($e);

            return response()->json(
                [
                    'error' => $e->getMessage(),
                    'message' => $checkStatusCodeError['message']
                ],
                $checkStatusCodeError['status_code']
            );
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

            return response()->json($this->service->update($request->all(), $id));
        } catch (\Exception $e) {

            $checkStatusCodeError = $this->checkStatusCodeError($e);

            return response()->json(
                [
                    'error' => $e->getMessage(),
                    'message' => $checkStatusCodeError['message']
                ],
                $checkStatusCodeError['status_code']
            );
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {

            $this->service->destroy($id);

            return response()->json('Dados carregados com sucesso!');
        } catch (\Exception $e) {

            $checkStatusCodeError = $this->checkStatusCodeError($e);

            return response()->json(
                [
                    'error' => $e->getMessage(),
                    'message' => $checkStatusCodeError['message']
                ],
                $checkStatusCodeError['status_code']
            );
        }
    }
}
