<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * @return JsonResponse
     */
    public static function statusResponse(): JsonResponse
    {
        return response()->json([
            'status' => true,
        ]);
    }
}
