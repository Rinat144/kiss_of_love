<?php

namespace App\Services\Game\Repositories;

use App\Models\Game;
use App\Services\Game\DTOs\CreateGameDto;
use App\Services\Game\Enum\StatusGameEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GameRepository
{
    /**
     * @param CreateGameDto $dto
     * @return true
     */
    final public function createGameMan(CreateGameDto $dto): true
    {
        $game = new Game();

        $game->fourth_user_id = auth()->id();
        $game->fourth_user_info = ['question' => $dto->question];
        $game->status = StatusGameEnum::ACTIVE->value;
        $game->save();

        return true;
    }

    /**
     * @param CreateGameDto $dto
     * @return true
     */
    final public function createGameWoman(CreateGameDto $dto): true
    {
        $game = new Game();

        $game->first_user_id = auth()->id();
        $game->first_user_info = ['question' => $dto->question];
        $game->status = StatusGameEnum::ACTIVE->value;
        $game->save();

        return true;
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
     * @return void
     */
    final public function updateDataThePlayer(string $userIdColumn, string $userInfoColumn, string $question, Game $game): void
    {
        $game->{$userIdColumn} = auth()->id();
        $game->{$userInfoColumn} = ['question' => $question];
        $game->save();
    }

    /**
     * @param Game $game
     * @return void
     */
    final public function changeTheStatusOfTheGame(Game $game): void
    {
        $game->status = StatusGameEnum::PROCESS->value;
        $game->save();
    }
}
