<?php

namespace App\Services\City\Repositories;

use App\Models\City;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CityRepository
{
    private const NUMBER_OF_ELEMENTS_FOR_PAGINATION = 20;

    /**
     * @return LengthAwarePaginator
     */
    public function getAllCities(): LengthAwarePaginator
    {
        return City::query()->select('id', 'city')->paginate(self::NUMBER_OF_ELEMENTS_FOR_PAGINATION);
    }
}
