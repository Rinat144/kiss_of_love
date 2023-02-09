<?php

namespace App\Http\Controllers;

use App\Services\Game\Exception\NotFoundGameException;
use App\Services\Game\GameService;
use App\Services\Game\Requests\CreateGameRequest;
use App\Services\Game\Requests\SearchActiveGameRequest;
use App\Services\Game\Resources\GameResource;
use App\Services\Game\Resources\GetInfoTheGameResource;
use App\Services\Game\Resources\SearchActiveGameResource;

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
     * @return GameResource
     */
    final public function createGame(CreateGameRequest $createGameRequest): GameResource
    {
        $game = $this->gameService->createGame($createGameRequest->getDto());

        return new GameResource($game);
    }

    /**
     * @param int $gameId
     * @return GetInfoTheGameResource
     * @throws NotFoundGameException
     */
    final public function getInfoTheGame(int $gameId): GetInfoTheGameResource
    {
        $infoTheGame = $this->gameService->getInfoAboutTheMatchPlayed($gameId);

        return new GetInfoTheGameResource($infoTheGame);
    }

    /**
     * @param SearchActiveGameRequest $searchActiveGameRequest
     * @return SearchActiveGameResource
     * @throws NotFoundGameException
     */
    final public function searchActiveGame(SearchActiveGameRequest $searchActiveGameRequest): SearchActiveGameResource
    {
       $game = $this->gameService->searchActiveGame($searchActiveGameRequest->getDto());

        return new SearchActiveGameResource($game);
    }
}