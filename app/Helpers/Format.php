<?php

use Illuminate\Http\JsonResponse;

function responseFormat(int $code, string $message = 'ok', $params = null): JsonResponse
{
    if ($code < 200 || $code > 500) $code = 400;
    return response()->json([
        'status_code' => $code,
        'status_message' => $message,
        'status_data' => $params,
    ], $code);
}
