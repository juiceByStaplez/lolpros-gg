<?php

namespace App\Event\Core\Team;

use App\Entity\Core\Team\Team;
use Symfony\Contracts\EventDispatcher\Event;

class TeamEvent extends Event
{
    const CREATED = 'team.created';
    const UPDATED = 'team.updated';
    const DELETED = 'team.deleted';

    /**
     * @var Team
     */
    private $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function getTeam(): Team
    {
        return $this->team;
    }
}
