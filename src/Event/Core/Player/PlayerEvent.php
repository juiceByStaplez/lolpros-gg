<?php

namespace App\Event\Core\Player;

use App\Entity\Core\Player\Player;
use Symfony\Contracts\EventDispatcher\Event;

class PlayerEvent extends Event
{
    const CREATED = 'player.created';
    const UPDATED = 'player.updated';
    const DELETED = 'player.deleted';

    /**
     * @var Player
     */
    private $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }
}
