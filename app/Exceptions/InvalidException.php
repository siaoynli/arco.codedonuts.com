<?php

namespace App\Exceptions;

use Exception;

class InvalidException extends Exception
{
    private function __construct($message)
    {
        parent::__construct($message);
    }

    public static function withMessage(string $message): static
    {
        return new static($message);
    }
}
