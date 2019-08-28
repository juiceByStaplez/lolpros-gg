<?php

namespace App\Model\LeagueOfLegends\Ladder;

use JMS\Serializer\Annotation as Serializer;

class RiotAccountLadder
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
    public $riotId;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $accountId;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $profileIconId;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $summonerName;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $rank;

    /**
     * @var int
     * @Serializer\Type("integer")
     */
    public $leaguePoints;

    /**
     * @var int
     * @Serializer\Type("integer")
     */
    public $games;

    /**
     * @var PlayerLadder
     * @Serializer\Type("App\Model\LeagueOfLegends\Player\Ladder\PlayerLadder")
     */
    public $player;
}
