<?php

namespace App\Services\User\Exceptions;

use App\Services\User\Enum\StatusBalanceEnum;
use Exception;

class UserApiException extends Exception
{
    public function __construct(StatusBalanceEnum $enum)
    {
        parent::__construct($enum->value);
    }
}
