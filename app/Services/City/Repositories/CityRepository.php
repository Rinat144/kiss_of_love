<?php

namespace App\Services\City\Repositories;

use App\Models\City;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CityRepository
{
    /**
     * @return LengthAwarePaginator
     */
    public function getAllCities(): LengthAwarePaginator
    {
        return City::query()->select('id', 'city')->paginate(20);
    }
}
