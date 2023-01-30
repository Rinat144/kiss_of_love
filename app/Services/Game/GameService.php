<?php

namespace App\Services\Game;

use App\Models\Game;
use App\Models\User;
use App\Services\Auth\Enum\GenderSelectionEnum;
use App\Services\Game\DTOs\CreateGameDto;
use App\Services\Game\Exception\NotFoundGameException;
use App\Services\Game\Repositories\GameRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class GameService
{
    /**
     * @param GameRepository $gameRepository
     * @param User $user
     */
    public function __construct(
        private readonly GameRepository $gameRepository,
        private Authenticatable         $user,
    )
    {
        $this->user = auth()->user();
    }

    /**
     * @param CreateGameDto $createGameDto
     * @return true
     */
    final public function createGame(CreateGameDto $createGameDto): true
    {
        if ($this->user->gender === GenderSelectionEnum::MAN) {
            return $this->gameRepository->createGameMan($createGameDto);
        }

        return $this->gameRepository->createGameWoman($createGameDto);
    }

    /**
     * @param CreateGameDto $createGameDto
     * @return void
     * @throws NotFoundGameException
     */
    final public function searchActiveGame(CreateGameDto $createGameDto): void
    {
        $game = match ($this->user->gender) {
            GenderSelectionEnum::MAN => $this->gameRepository->findGameForMan(),
            GenderSelectionEnum::WOMAN => $this->gameRepository->findGameForWoman(),
        };

        if (!$game) {
            throw new NotFoundGameException('No active games');
        }

        $this->getGameByGender($game, $createGameDto);
    }

    /**
     * @param Game|Model $game
     * @param CreateGameDto $createGameDto
     * @return void
     */
    private function getGameByGender(Game|Model $game, CreateGameDto $createGameDto): void
    {
        if ($this->user->gender === GenderSelectionEnum::MAN) {
            $this->includeUserGameForMan($game, $createGameDto);
        } else {
            $this->includeUserGameForWoman($game, $createGameDto);
        }
    }

    /**
     * @param Game $game
     * @param CreateGameDto $createGameDto
     * @return void
     */
    private function includeUserGameForMan(Game $game, CreateGameDto $createGameDto): void
    {
        if (!$game->fourth_user_id) {
            $this->gameRepository->updateDataThePlayer('fourth_user_id', 'fourth_user_info', $createGameDto->question, $game);
        } elseif (!$game->fifth_user_id) {
            $this->gameRepository->updateDataThePlayer('fifth_user_id', 'fifth_user_info', $createGameDto->question, $game);
        } elseif (!$game->sixth_user_id) {
            $this->gameRepository->updateDataThePlayer('sixth_user_id', 'sixth_user_info', $createGameDto->question, $game);
            $this->updateStatusGameForMan($game);
        }
    }

    /**
     * @param Game $game
     * @param CreateGameDto $createGameDto
     * @return void
     */
    private function includeUserGameForWoman(Game $game, CreateGameDto $createGameDto): void
    {
        if (!$game->first_user_id) {
            $this->gameRepository->updateDataThePlayer('first_user_id', 'first_user_info', $createGameDto->question, $game);
        } elseif (!$game->second_user_id) {
            $this->gameRepository->updateDataThePlayer('second_user_id', 'second_user_info', $createGameDto->question, $game);
        } elseif (!$game->third_user_id) {
            $this->gameRepository->updateDataThePlayer('third_user_id', 'third_user_info', $createGameDto->question, $game);
            $this->updateStatusGameForWoman($game);
        }
    }

    /**
     * @param Game $game
     * @return void
     */
    private function updateStatusGameForMan(Game $game): void
    {
        if ($game->third_user_id !== null) {
            $this->gameRepository->changeTheStatusOfTheGame($game);
        }
    }

    /**
     * @param Game $game
     * @return void
     */
    private function updateStatusGameForWoman(Game $game): void
    {
        if ($game->sixth_user_id !== null) {
            $this->gameRepository->changeTheStatusOfTheGame($game);
        }
    }
}
