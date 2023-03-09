<?php

namespace App\Support;

use App\Models\Game;

class GameHelper
{
    /**
     * @param Game $game
     * @param int $userId
     * @return string
     */
    public static function getFieldInfoUser(Game $game, int $userId): string
    {
        return match ($userId) {
            $game->first_user_id => 'first_user_info',
            $game->second_user_id => 'second_user_info',
            $game->third_user_id => 'third_user_info',
            $game->fourth_user_id => 'fourth_user_info',
            $game->fifth_user_id => 'fifth_user_info',
            $game->sixth_user_id => 'sixth_user_info',
        };
    }
}
