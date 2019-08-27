<?php

namespace App\Exception\LeagueOfLegends;

use Throwable;

class AccountAlreadyExistsException extends \Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->code = 409;
        $this->message = 'Account already exists';
    }
}
