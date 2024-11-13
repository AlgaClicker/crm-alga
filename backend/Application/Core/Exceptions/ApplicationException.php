<?php

namespace Core\Exceptions;

use Throwable;
use Exception;

class ApplicationException extends Exception
{
    public function __construct($message = "", $code = 400, Throwable $previous = null)
    {

        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return $this->getCode();
    }
}
