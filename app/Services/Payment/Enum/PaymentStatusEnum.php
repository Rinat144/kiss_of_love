<?php

namespace App\Services\Payment\Enum;

enum PaymentStatusEnum: int
{
    case CREATED = 1;
    case PAID_FOR = 2;
    case RECEIVED = 3;
}
