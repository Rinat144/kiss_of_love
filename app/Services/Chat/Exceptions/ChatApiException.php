<?php

namespace App\Services\Chat\Exceptions;

use Exception;
use App\Services\Chat\Enum\ExceptionEnum;

class ChatApiException extends Exception
{
    public function __construct(ExceptionEnum $enum)
    {
        parent::__construct($enum->value);
    }
}
