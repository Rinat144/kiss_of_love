<?php

namespace App\Services\Transaction\Enum;

enum TypeTurnoverEnum: int
{
    case INFLOW = 1;
    case OUTLAY = 2;
}
