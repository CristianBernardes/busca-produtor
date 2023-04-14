<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Repositories\StateCitiesRepository;
use Illuminate\Http\Request;

/**
 *
 */
class StateCitiesController extends Controller
{
    /**
     * @var StateCitiesRepository
     */
    private StateCitiesRepository $stateCitiesRepository;

    /**
     * @param StateCitiesRepository $stateCitiesRepository
     */
    public function __construct(StateCitiesRepository $stateCitiesRepository)
    {
        $this->stateCitiesRepository = $stateCitiesRepository;
    }

    /**
     * @param $uf
     * @return JsonResponse
     */
    public function index($uf = null): JsonResponse
    {
        return response()->json($this->stateCitiesRepository->index($uf));
    }
}
