<?php

namespace App\Services\Game;

use App\Models\Game;
use App\Models\User;
use App\Services\Auth\Enum\GenderSelectionEnum;
use App\Services\Game\DTOs\CreateGameDto;
use App\Services\Game\DTOs\SearchActiveGameDto;
use App\Services\Game\Exception\NotFoundGameException;
use App\Services\Game\Repositories\GameRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
     * @return Game
     */
    final public function createGame(CreateGameDto $createGameDto): Game
    {
        if ($this->user->gender === GenderSelectionEnum::MAN) {
            return $this->gameRepository->createGameMan($createGameDto);
        }

        return $this->gameRepository->createGameWoman($createGameDto);
    }

    /**
     * @param SearchActiveGameDto $searchActiveGameDto
     * @return Model|Builder|Game
     * @throws NotFoundGameException
     */
    final public function searchActiveGame(SearchActiveGameDto $searchActiveGameDto): Model|Builder|Game
    {
        $game = $this->checkAnActiveGameForPlayer();

        if ($game) {
            return $game;
        }

        $game = match ($this->user->gender) {
            GenderSelectionEnum::MAN => $this->gameRepository->findGameForMan(),
            GenderSelectionEnum::WOMAN => $this->gameRepository->findGameForWoman(),
        };

        if (!$game) {
            throw new NotFoundGameException('No active games');
        }

        return $this->distributeRheGameByGender($game, $searchActiveGameDto);
    }

    /**
     * @param int $game
     * @return Collection|array
     * @throws NotFoundGameException
     */
    final public function getInfoAboutTheMatchPlayed(int $game): Collection|array
    {
        $userId = $this->user->id;

        $infoAboutMatch = $this->gameRepository->getInfoAboutTheMatchPlayed($game, $userId);

        if ($infoAboutMatch->isEmpty()) {
            throw new NotFoundGameException('You didnt participate in this match');
        }

        return $infoAboutMatch;
    }

    /**
     * @return Model|Builder|null
     */
    private function checkAnActiveGameForPlayer(): Model|Builder|null
    {
        return match ($this->user->gender) {
            GenderSelectionEnum::MAN => $this->gameRepository->checkAnActiveGameForMan($this->user->id),
            GenderSelectionEnum::WOMAN => $this->gameRepository->checkAnActiveGameForWoman($this->user->id),
        };
    }

    /**
     * @param Game|Model $game
     * @param SearchActiveGameDto $searchActiveGameDto
     * @return Game
     */
    private function distributeRheGameByGender(Game|Model $game, SearchActiveGameDto $searchActiveGameDto): Game
    {
        if ($this->user->gender === GenderSelectionEnum::MAN) {
            return $this->includeUserGameForMan($game, $searchActiveGameDto);
        }

        return $this->includeUserGameForWoman($game, $searchActiveGameDto);
    }

    /**
     * @param Game $game
     * @param SearchActiveGameDto $searchActiveGameDto
     * @return Game
     */
    private function includeUserGameForMan(Game $game, SearchActiveGameDto $searchActiveGameDto): Game
    {
        if (!$game->fourth_user_id) {
            $game = $this->gameRepository->updateDataThePlayer('fourth_user_id', 'fourth_user_info', $searchActiveGameDto->question, $game);
        } elseif (!$game->fifth_user_id) {
            $game = $this->gameRepository->updateDataThePlayer('fifth_user_id', 'fifth_user_info', $searchActiveGameDto->question, $game);
        } elseif (!$game->sixth_user_id) {
            $game = $this->gameRepository->updateDataThePlayer('sixth_user_id', 'sixth_user_info', $searchActiveGameDto->question, $game);
            $this->updateStatusGameForMan($game);
        }

        return $game;
    }

    /**
     * @param Game $game
     * @param SearchActiveGameDto $searchActiveGameDto
     * @return Game
     */
    private function includeUserGameForWoman(Game $game, SearchActiveGameDto $searchActiveGameDto): Game
    {
        if (!$game->first_user_id) {
            $game = $this->gameRepository->updateDataThePlayer('first_user_id', 'first_user_info', $searchActiveGameDto->question, $game);
        } elseif (!$game->second_user_id) {
            $game = $this->gameRepository->updateDataThePlayer('second_user_id', 'second_user_info', $searchActiveGameDto->question, $game);
        } elseif (!$game->third_user_id) {
            $game = $this->gameRepository->updateDataThePlayer('third_user_id', 'third_user_info', $searchActiveGameDto->question, $game);
            $this->updateStatusGameForWoman($game);
        }

        return $game;
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
