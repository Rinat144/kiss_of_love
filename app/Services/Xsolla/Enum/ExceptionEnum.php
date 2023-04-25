<?php

namespace App\Services\Xsolla\Enum;

enum ExceptionEnum: string
{
    case NO_GIVEN_PRODUCT = 'There is no given product';
    case PAYMENT_NOT_FOUND = 'Payment not found';
    case INVALID_SIGNATURE_KEY = 'Invalid signature key';
}
