<?php

namespace App\Services\City\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

class CityRepository
{
    /**
     * @return Collection|array
     */
    final public function getAllCities(): Collection|array
    {
        return City::query()
            ->select('id', 'city')
            ->get();
    }
}
