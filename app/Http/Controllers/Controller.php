<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @param Exception $e
     * @return array
     */
    public function checkStatusCodeError(Exception $e): array
    {
        $statusCode = 400;

        $message = $e->getMessage();

        if (array_key_exists($e->getCode(), httpStatusCodes())) {
            $statusCode = $e->getCode();
            $message = httpStatusCodes()[$e->getCode()];
        }

        return [
            'status_code' => $statusCode,
            'message' => $message
        ];
    }
}
