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
    )
    {
    }

    /**
     * @return AnonymousResourceCollection
     */
    final public function getAllCities(): AnonymousResourceCollection
    {
        $allCities = $this->cityService->getAllCities();

        return CityResource::collection($allCities);
    }
}
