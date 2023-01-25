<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\Game\GameService;
use App\Services\Game\Requests\CreateGameRequest;
use App\Services\Game\Resources\GameResource;
use Illuminate\Http\JsonResponse;
use JsonException;

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
     * @throws JsonException
     */
    final public function createGame(CreateGameRequest $createGameRequest): JsonResponse
    {
        $game = $this->gameService->createGame($createGameRequest);

        return response()->json([
            $game
        ]);
    }

    /**
     * @param Game $gameId
     * @return GameResource
     */
    final public function getInfoTheGame(Game $gameId): GameResource
    {
        return new GameResource($gameId);
    }
}
