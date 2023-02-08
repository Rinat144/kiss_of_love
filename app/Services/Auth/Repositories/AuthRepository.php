<?php

namespace App\Services\Auth\Repositories;

use App\Models\User;
use App\Services\Auth\DTOs\RegisterDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AuthRepository
{
    /**
     * @param RegisterDto $authDTO
     * @return Model|Builder
     */
    final public function register(RegisterDto $authDTO): Model|Builder
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
