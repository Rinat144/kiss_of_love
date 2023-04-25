<?php

namespace App\Services\Xsolla\Exceptions;

use App\Services\Xsolla\Enum\ExceptionEnum;
use Exception;

class XsollaApiException extends Exception
{
    public function __construct(ExceptionEnum $enum)
    {
        parent::__construct($enum->value);
    }
}
