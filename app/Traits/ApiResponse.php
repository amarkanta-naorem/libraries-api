<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     */
    protected function successResponse(mixed $data = null, string $message = 'Request successful.', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'status_code' => $status,
            'message' => $message,
            'data' => $data,
            'error' => null,
        ], $status);
    }

    /**
     * Return an error JSON response.
     */
    protected function errorResponse(string $message = 'An error occurred.', mixed $error = null, int $status = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status_code' => $status,
            'message' => $message,
            'data' => null,
            'error' => $error,
        ], $status);
    }
}
