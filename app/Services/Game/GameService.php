<?php

namespace App\Services\Game;

use App\Models\Game;
use App\Services\Auth\Enum\GenderSelectionEnum;
use App\Services\Game\Repositories\GameRepository;
use App\Services\Game\Requests\CreateGameRequest;
use JsonException;

readonly class GameService
{
    /**
     * @param GameRepository $gameRepository
     */
    public function __construct(
        private GameRepository $gameRepository
    )
    {
    }

    /**
     * @param CreateGameRequest $createGameRequest
     * @return Game
     * @throws JsonException
     */
    final public function createGame(CreateGameRequest $createGameRequest): Game
    {
        if ($createGameRequest->user()->gender === GenderSelectionEnum::MAN) {
            return $this->gameRepository->createGameMan($createGameRequest->getDto());
        }

        return $this->gameRepository->createGameWoman($createGameRequest->getDto());
    }
}
