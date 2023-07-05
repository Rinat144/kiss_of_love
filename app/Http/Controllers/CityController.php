<?php

namespace App\Http\Controllers;

use App\Services\City\CityService;
use App\Services\City\Resources\CityResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CityController extends Controller
{
    /**
     * @param CityService $cityService
     */
    public function __construct(
        private readonly CityService $cityService,
    ) {
    }

    /**
     * @return array
     */
    final public function getAllCities(): array
    {
        $allCities = $this->cityService->getAllCities();

        return [
            "code" => 200,
            "message" => "ok",
            "data" => CityResource::collection($allCities)
        ];
    }
}
