<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\Game\Exception\NotFoundGameException;
use App\Services\Game\GameService;
use App\Services\Game\Requests\CreateGameRequest;
use App\Services\Game\Resources\GameResource;
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
        $game = $this->gameService->createGame($createGameRequest->getDto());

        return response()->json([
            $game
        ]);
    }

    /**
     * @param Game $game
     * @return GameResource
     */
    final public function getInfoTheGame(Game $game): GameResource
    {
        return new GameResource($game);
    }

    /**
     * @param CreateGameRequest $createGameRequest
     * @return JsonResponse
     * @throws NotFoundGameException
     */
    final public function searchActiveGame(CreateGameRequest $createGameRequest): JsonResponse
    {
        $this->gameService->searchActiveGame($createGameRequest->getDto());

        return response()->json([
            true
        ]);
    }
}
