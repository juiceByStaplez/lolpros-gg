<?php

namespace App\Model\LeagueOfLegends;

use App\Entity\LeagueOfLegends\Player\Player;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\UuidInterface;

class RiotAccount
{
    /**
     * @var UuidInterface
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
     * @var bool
     * @Serializer\Type("boolean")
     */
    public $smurf;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $summonerName;

    /**
     * @var \DateTime
     * @Serializer\Type("DateTime")
     */
    public $updatedAt;

    /**
     * @var Player
     * @Serializer\Type("App\Entity\LeagueOfLegends\Player\Player")
     */
    public $player;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    public $currentRank;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    public $peakRank;
}
