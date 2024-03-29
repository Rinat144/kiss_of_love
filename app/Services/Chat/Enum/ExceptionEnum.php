<?php

namespace App\Services\Chat\Enum;

enum ExceptionEnum: string
{
    case ID_DIDNT_MATCH = "Your choice didn't match";
    case NO_ACTIVE_GAME = "No active game";
    case NO_ACTIVE_CHAT = "No active chat participants";
    case NO_ID_GAME_USER = "The user ID is incorrectly selected";
}
