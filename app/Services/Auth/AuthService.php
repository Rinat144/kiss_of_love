<?php

namespace App\Services\Auth;

use App\Services\Auth\DTOs\RegisterDto;
use App\Services\Auth\Repositories\AuthRepository;
use App\Services\Auth\Requests\LoginRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * @param AuthRepository $authRepository
     */
    public function __construct(
        private readonly AuthRepository $authRepository
    )
    {
    }

    /**
     * @param RegisterDto $authDTO
     * @return Model|Builder
     */
    public function register(RegisterDto $authDTO): Model|Builder
    {
        return $this->authRepository->register($authDTO);
    }

    /**
     * @param LoginRequest $loginRequest
     * @return string
     * @throws AuthenticationException
     */
    public function login(LoginRequest $loginRequest): string
    {
        if (!$token = Auth::attempt($loginRequest->validated())) {
            throw new AuthenticationException();
        }

        return $token;
    }
}
