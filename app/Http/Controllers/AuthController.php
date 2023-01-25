<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthService;
use App\Services\Auth\Requests\LoginRequest;
use App\Services\Auth\Requests\RegisterRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private const TOKEN_EXPIRATION_TIME = 60;

    /**
     * @param AuthService $authService
     */
    public function __construct(
        private readonly AuthService $authService
    )
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @param LoginRequest $loginRequest
     * @return JsonResponse
     * @throws AuthenticationException
     */
    final public function login(LoginRequest $loginRequest): JsonResponse
    {
        $tokenData = $this->authService->login($loginRequest);

        return $this->createNewToken($tokenData);
    }

    /**
     * @param RegisterRequest $authRequest
     * @return JsonResponse
     */
    final public function register(RegisterRequest $authRequest): JsonResponse
    {
        $this->authService->register($authRequest->getDto());

        return response()->json([
            'status' => true
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    final public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    final public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    final public function userProfile(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * @param string $tokenData
     * @return JsonResponse
     */
    private function createNewToken(string $tokenData): JsonResponse
    {
        return response()->json([
            'access_token' => $tokenData,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * self::TOKEN_EXPIRATION_TIME,
            'user' => auth()->user()
        ]);
    }
}
