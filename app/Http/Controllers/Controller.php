<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Exception;

/**
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param Exception $e
     * @return JsonResponse
     */
    public function checkStatusCodeError(Exception $e): JsonResponse
    {
        $statusCode = 400;

        $message = $e->getMessage();

        if (array_key_exists($e->getCode(), httpStatusCodes())) {
            $statusCode = $e->getCode();
        }

        return response()->json([
            'message' => $message,
            'errors' => null
        ], $statusCode);
    }
}
