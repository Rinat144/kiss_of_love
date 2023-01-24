<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\Auth\Enum\GenderSelectionEnum;
use App\Services\Game\GameService;
use App\Services\Game\Requests\CreateGameRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * @param GameService $gameService
     */
    public function __construct(
        private GameService $gameService
    )
    {
    }

    /**
     * @param CreateGameRequest $createGameRequest
     * @return JsonResponse
     */
    public function createGame(CreateGameRequest $createGameRequest): JsonResponse
    {
        $createGame = $this->gameService->createGame($createGameRequest);

        return response()->json([
            $createGame
        ]);
    }
}
