<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Services\City\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    private CityService $cityService;

    /**
     * @param CityService $cityService
     */
    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

}
