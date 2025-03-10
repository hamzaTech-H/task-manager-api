<?php

namespace App\Traits;

trait ApiResponse
{
    protected function success($message, $data = [], $statusCode = 200) 
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
            'data' => $data
        ],$statusCode);
    }

    protected function error($message, $statusCode = 400)
    {
        return response()->json([
            'status' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
}
