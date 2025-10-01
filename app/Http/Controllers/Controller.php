<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //constanta
    const LATEST = 'desc';
    const OLDEST = 'asc';

    protected function ResponseOk($data, string $message = 'no message', int $statusCode = 200)
    {
        return response()->json([
            'status' => 'Successfully',
            'code' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    protected function ResponseError(string $message = 'no message', int $statusCode = 422)
    {
        return response()->json([
            'status' => 'Error',
            'code' => $statusCode,
            'message' => $message,
        ], $statusCode);
    }
}
