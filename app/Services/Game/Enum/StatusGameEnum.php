<?php

namespace App\Services\Game\Enum;

enum StatusGameEnum: int
{
    case ACTIVE = 1;
    case PROCESS = 2;
    case END = 3;
}
