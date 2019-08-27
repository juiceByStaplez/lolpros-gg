<?php

namespace App\Manager\LeagueOfLegends\Riot;

use RiotAPI\LeagueAPI\Definitions\Region;
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Objects\CurrentGameInfo;

class RiotSpectatorManager
{
    /**
     * @var LeagueAPI
     */
    private $api;

    public function __construct(string $apiKey)
    {
        $this->api = new LeagueAPI([
            LeagueAPI::SET_KEY => $apiKey,
            LeagueAPI::SET_REGION => Region::EUROPE_WEST,
            LeagueAPI::SET_VERIFY_SSL => false,
        ]);
    }

    public function findGame(string $name): CurrentGameInfo
    {
        return $this->api->getCurrentGameInfo($name);
    }
}
