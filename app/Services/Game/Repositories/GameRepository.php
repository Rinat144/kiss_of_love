<?php

namespace App\Services\Game\Repositories;

use App\Models\Game;
use App\Services\Game\DTOs\CreateGameDto;
use App\Services\Game\Enum\StatusGameEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GameRepository
{
    /**
     * @param CreateGameDto $dto
     * @return Game
     */
    final public function createGameMan(CreateGameDto $dto): Game
    {
        $game = new Game();

        $game->fourth_user_id = auth()->id();
        $game->fourth_user_info = ['question' => $dto->question];
        $game->status = StatusGameEnum::ACTIVE;
        $game->save();

        return $game;
    }

    /**
     * @param CreateGameDto $dto
     * @return Game
     */
    final public function createGameWoman(CreateGameDto $dto): Game
    {
        $game = new Game();

        $game->first_user_id = auth()->id();
        $game->first_user_info = ['question' => $dto->question];
        $game->status = StatusGameEnum::ACTIVE;
        $game->save();

        return $game;
    }

    /**
     * @return Model|Builder|null
     */
    final public function findGameForMan(): Model|Builder|null
    {
        return Game::query()
            ->where('status', '=', StatusGameEnum::ACTIVE)
            ->where(function ($query) {
                $query->orWhereNull('fourth_user_id')
                    ->orWhereNull('fifth_user_id')
                    ->orWhereNull('sixth_user_id');
            })
            ->oldest()
            ->first();
    }

    /**
     * @return Model|Builder|null
     */
    final public function findGameForWoman(): Model|Builder|null
    {
        return Game::query()
            ->where('status', '=', StatusGameEnum::ACTIVE)
            ->where(function ($query) {
                $query->orWhereNull('first_user_id')
                    ->orWhereNull('second_user_id')
                    ->orWhereNull('third_user_id');
            })
            ->oldest()
            ->first();
    }

    /**
     * @param string $userIdColumn
     * @param string $userInfoColumn
     * @param string $question
     * @param Game $game
     * @return Game
     */
    final public function updateDataThePlayer(string $userIdColumn, string $userInfoColumn, string $question, Game $game): Game
    {
        $game->{$userIdColumn} = auth()->id();
        $game->{$userInfoColumn} = ['question' => $question];
        $game->save();

        return $game;
    }

    /**
     * @param Game $game
     * @return void
     */
    final public function changeTheStatusOfTheGame(Game $game): void
    {
        $game->status = StatusGameEnum::PROCESS;
        $game->save();
    }

    /**
     * @param int $game
     * @param int $userId
     * @return Collection|array
     */
    final public function getInfoAboutTheMatchPlayed(int $game, int $userId): Collection|array
    {
        return Game::query()
            ->where('id', '=', $game)
            ->where(function ($query) use ($userId) {
                $query->orWhere('first_user_id', '=', $userId)
                    ->orWhere('second_user_id', '=', $userId)
                    ->orWhere('third_user_id', '=', $userId)
                    ->orWhere('fourth_user_id', '=', $userId)
                    ->orWhere('fifth_user_id', '=', $userId)
                    ->orWhere('sixth_user_id', '=', $userId);
            })
            ->get();
    }

    /**
     * @param int $userId
     * @return Model|Builder|null
     */
    final public function checkAnActiveGameForMan(int $userId): Model|Builder|null
    {
        return Game::query()
            ->Where('status', '=', StatusGameEnum::ACTIVE)
            ->where(function ($query) use ($userId) {
                $query->orWhere('fourth_user_id', '=', $userId)
                    ->orWhere('fifth_user_id', '=', $userId)
                    ->orWhere('sixth_user_id', '=', $userId);
            })
            ->orWhere('status', '=', StatusGameEnum::PROCESS)
            ->where(function ($query) use ($userId) {
                $query->orWhere('fourth_user_id', '=', $userId)
                    ->orWhere('fifth_user_id', '=', $userId)
                    ->orWhere('sixth_user_id', '=', $userId);
            })
            ->first();
    }

    /**
     * @param int $userId
     * @return Model|Builder|null
     */
    final public function checkAnActiveGameForWoman(int $userId): Model|Builder|null
    {
        return Game::query()
            ->Where('status', '=', StatusGameEnum::ACTIVE)
            ->where(function ($query) use ($userId) {
                $query->orWhere('first_user_id', '=', $userId)
                    ->orWhere('second_user_id', '=', $userId)
                    ->orWhere('third_user_id', '=', $userId);
            })
            ->orWhere('status', '=', StatusGameEnum::PROCESS)
            ->where(function ($query) use ($userId) {
                $query->orWhere('first_user_id', '=', $userId)
                    ->orWhere('second_user_id', '=', $userId)
                    ->orWhere('third_user_id', '=', $userId);
            })
            ->first();
    }
}
