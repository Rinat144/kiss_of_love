<?php

namespace App\Services\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\DTOs\RegisterDTO;
use App\Services\Auth\Repositories\AuthRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    private AuthRepository $authRepository;

    /**
     * @param AuthRepository $authRepository
     */
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @param RegisterDTO $authDTO
     * @return Model|Builder
     */
    public function register(RegisterDTO $authDTO): Model|Builder
    {
        return $this->authRepository->register($authDTO);
    }

    /**
     * @param LoginRequest $loginRequest
     * @return JsonResponse|string
     */
    public function login(LoginRequest $loginRequest): JsonResponse|string
    {
        if (!$token = Auth::attempt($loginRequest->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $token;
    }
}
