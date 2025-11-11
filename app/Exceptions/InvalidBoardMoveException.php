<?php

namespace App\Exceptions;

use DomainException;

class InvalidBoardMoveException extends DomainException
{
    public function __construct(string $message = "Invalid board move.")
    {
        parent::__construct($message);
    }
}
