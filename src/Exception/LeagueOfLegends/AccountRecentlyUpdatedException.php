<?php

namespace App\Exception\LeagueOfLegends;

use DateInterval;
use Throwable;

class AccountRecentlyUpdatedException extends \Exception
{
    /**
     * @var DateInterval
     */
    public $diff;

    public function __construct(DateInterval $diff = null, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->code = 422;
        $message = 'Accounts can only be updated once every hour.';
        $message .= $diff ? sprintf(' Last update %d minutes ago', $diff->i + $diff->h / 60) : '';
        $this->message = $message;
        $this->diff = $diff;
    }
}
