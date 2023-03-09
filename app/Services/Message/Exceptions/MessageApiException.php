<?php

namespace App\Services\Message\Exceptions;

use Exception;
use App\Services\Message\Enum\ExceptionEnum;

class MessageApiException extends Exception
{
    public function __construct(ExceptionEnum $enum)
    {
        parent::__construct($enum->value);
    }
}
