<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ApiResponse
{
    public static function success($data = [], $message = "", $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'payload' => $data,
            'message' => $message,
            'status_code' => $statusCode,
        ], $statusCode);
    }

    public static function error($message = "", $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status_code' => $statusCode,
        ], $statusCode);
    }
}
