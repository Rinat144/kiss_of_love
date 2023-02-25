<?php

namespace App\Services\Game\Exceptions;

use Exception;
use App\Services\Game\Enum\ExceptionEnum;

class GameApiException extends Exception
{
    public function __construct(ExceptionEnum $enum)
    {
        parent::__construct($enum->value);
    }
}
