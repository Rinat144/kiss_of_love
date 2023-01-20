<?php

namespace App\Services\City;

use App\Services\City\Repositories\CityRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CityService
{
    /**
     * @param CityRepository $cityRepository
     */
    public function __construct(
        private readonly CityRepository $cityRepository
    )
    {
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllCities(): LengthAwarePaginator
    {
        return $this->cityRepository->getAllCities();
    }
}
