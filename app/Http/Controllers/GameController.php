<?php

namespace App\Http\Controllers;

use App\Services\Game\GameService;
use App\Services\Game\Requests\CreateGameRequest;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    /**
     * @param GameService $gameService
     */
    public function __construct(
        private readonly GameService $gameService
    )
    {
    }

    /**
     * @param CreateGameRequest $createGameRequest
     * @return JsonResponse
     */
    final public function createGame(CreateGameRequest $createGameRequest): JsonResponse
    {
        $game = $this->gameService->createGame($createGameRequest);

        return response()->json([
            $game
        ]);
    }
}
