<?php

namespace App\Services\City;

use App\Services\City\Repositories\CityRepository;
use Illuminate\Database\Eloquent\Collection;

readonly class CityService
{
    /**
     * @param CityRepository $cityRepository
     */
    public function __construct(
        private CityRepository $cityRepository
    )
    {
    }

    /**
     * @return Collection|array
     */
    final public function getAllCities(): Collection|array
    {
        return $this->cityRepository->getAllCities();
    }
}
