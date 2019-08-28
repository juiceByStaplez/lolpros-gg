<?php

namespace App\Model\LeagueOfLegends\Ladder;

use JMS\Serializer\Annotation as Serializer;

class PlayerLadder
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $uuid;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $slug;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $country;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $position;

    /**
     * @var int
     * @Serializer\Type("integer")
     */
    public $score;

    /**
     * @var RiotAccountLadder
     * @Serializer\Type("App\Model\LeagueOfLegends\Player\Ladder\RiotAccountLadder")
     */
    public $account;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    public $team;
}
