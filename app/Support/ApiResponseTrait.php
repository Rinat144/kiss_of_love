<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * @param bool $status
     * @return JsonResponse
     */
    public static function statusResponse(bool $status): JsonResponse
    {
        return response()->json([
            'status' => $status,
        ]);
    }
}
