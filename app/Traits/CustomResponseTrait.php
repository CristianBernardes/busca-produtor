<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

trait CustomResponseTrait
{

    /**
     * @param string $message
     * @param int $status
     * @param array $data
     * @param Throwable|null $exception
     * @return JsonResponse
     */
    public function customJsonResponse(string $message = '', array $data = [], int $status = 200, ?Throwable $exception = null): JsonResponse
    {
        if ($status >= 500) {
            Log::error($message, ['exception' => $exception]);
        }

        return response()->json([
            'error' => $status != 200 && $status != 201 && $status != 202 && $status != 204,
            'message' => $message,
            'response' => $data,
        ], $status);
    }
}
