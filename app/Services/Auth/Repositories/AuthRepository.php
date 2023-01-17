<?php

namespace App\Services\Auth\Repositories;

use App\Models\User;
use App\Services\Auth\DTOs\RegisterDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AuthRepository
{
    /**
     * @param RegisterDTO $authDTO
     * @return Model|Builder
     */
    public function register(RegisterDTO $authDTO): Model|Builder
    {
        return User::query()->create([
            'name' => $authDTO->name,
            'date_of_birth' => $authDTO->date_of_birth,
            'gender' => $authDTO->gender,
            'login' => $authDTO->login,
            'password' => bcrypt($authDTO->password),
            'city_id' => $authDTO->city_id,
        ]);
    }
}
