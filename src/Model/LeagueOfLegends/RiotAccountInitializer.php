<?php

namespace App\Model\LeagueOfLegends;

use App\Entity\LeagueOfLegends\Player\Player;
use App\Entity\LeagueOfLegends\Player\Ranking;
use App\Entity\LeagueOfLegends\Player\RiotAccount;
use App\Entity\LeagueOfLegends\Player\SummonerName;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

class RiotAccountInitializer
{
    /**
     * @var RiotAccount
     * @Serializer\Type("LeagueOfLegendsBundle\Entity\Player\RiotAccount")
     */
    public $riotAccount;

    /**
     * @var SummonerName
     * @Serializer\Type("LeagueOfLegendsBundle\Entity\Player\SummonerName")
     */
    public $summonerName;

    /**
     * @var Player
     * @Serializer\Type("LeagueOfLegendsBundle\Entity\Player\Player")
     */
    public $player;

    /**
     * @var ArrayCollection|Ranking[]
     * @Serializer\Type("array<LeagueOfLegendsBundle\Entity\Player\Ranking>")
     */
    public $rankings;
}
