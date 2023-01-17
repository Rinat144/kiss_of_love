<?php

namespace App\Services\City;

use App\Services\City\Repositories\CityRepository;

class CityService
{
    private CityRepository $cityRepository;

    /**
     * @param CityRepository $cityRepository
     */
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

}
