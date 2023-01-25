<?php

namespace App\Services\Game\Repositories;

use App\Models\Game;
use App\Services\Game\DTOs\CreateGameDto;
use App\Services\Game\Enum\StatusGameEnum;
use JsonException;

class GameRepository
{
    /**
     * @param CreateGameDto $dto
     * @return Game
     * @throws JsonException
     */
    final public function createGameMan(CreateGameDto $dto): Game
    {
        $game = new Game();

        $game->fourth_user_id = auth()->id();
        $game->fourth_user_info = json_encode(['question' => $dto->question], JSON_THROW_ON_ERROR);
        $game->status = StatusGameEnum::ACTIVE->value;
        $game->save();

        return $game;
    }

    /**
     * @param CreateGameDto $dto
     * @return Game
     * @throws JsonException
     */
    final public function createGameWoman(CreateGameDto $dto): Game
    {
        $game = new Game();

        $game->first_user_id = auth()->id();
        $game->first_user_info = json_encode(["question" => $dto->question], JSON_THROW_ON_ERROR);
        $game->status = StatusGameEnum::ACTIVE->value;
        $game->save();

        return $game;
    }
}
