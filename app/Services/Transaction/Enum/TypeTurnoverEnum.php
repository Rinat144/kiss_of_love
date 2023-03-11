<?php

namespace App\Services\Transaction\Enum;

enum TypeTurnoverEnum: string
{
    case INFLOW = "inflow";
    case OUTLAY = 'outlay';
}
