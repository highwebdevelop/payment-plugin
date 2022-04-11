<?php

namespace Payment\System\App\Exceptions;

use Exception;

abstract class BusinessLogicException extends Exception
{
    public abstract function statusCode(): string;
    public abstract function status(): string;
}
