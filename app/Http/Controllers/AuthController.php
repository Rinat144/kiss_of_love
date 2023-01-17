<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthService $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @param LoginRequest $loginRequest
     * @return JsonResponse
     */
    public function login(LoginRequest $loginRequest): JsonResponse
    {
        $tokenData = $this->authService->login($loginRequest);

        return $this->createNewToken($tokenData);
    }

    /**
     * @param RegisterRequest $authRequest
     * @return JsonResponse
     */
    public function register(RegisterRequest $authRequest): JsonResponse
    {
        $authData = $this->authService->register($authRequest->getDto());

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $authData
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function userProfile(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function createNewToken($tokenData): JsonResponse
    {
        return response()->json([
            'access_token' => $tokenData,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
