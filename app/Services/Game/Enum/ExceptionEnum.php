<?php

namespace App\Services\Game\Enum;

enum ExceptionEnum: string
{
    case NOT_FOUND_GAME = "You didnt participate in this match";
    case NO_ACTIVE_GAME = "No active game";
}
