<?php

namespace App\Manager\LeagueOfLegends\Riot;

use RiotAPI\LeagueAPI\Definitions\Region;
use RiotAPI\LeagueAPI\LeagueAPI;
use RiotAPI\LeagueAPI\Objects\SummonerDto;

class RiotSummonerManager
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

    public function findPlayer(string $name): SummonerDto
    {
        return $this->api->getSummonerByName($name);
    }

    public function getForId(string $id): SummonerDto
    {
        return $this->api->getSummoner($id);
    }

    public function getPuuidForName($name): SummonerDto
    {
        return $this->api->getSummonerByName(rawurlencode($name));
    }
}
