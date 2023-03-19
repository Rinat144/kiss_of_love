<?php

namespace App\Services\User\Exceptions;

use App\Services\User\Enum\ExceptionEnum;
use Exception;

class UserApiException extends Exception
{
    public function __construct(ExceptionEnum $enum)
    {
        parent::__construct($enum->value);
    }
}
