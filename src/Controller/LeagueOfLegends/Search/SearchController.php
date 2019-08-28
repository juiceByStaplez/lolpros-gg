<?php

namespace App\Controller\LeagueOfLegends\Search;

use App\Controller\APIController;
use App\Manager\LeagueOfLegends\Search\SearchManager;
use App\Model\Player\Ladder\SearchResult;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Prefix;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @NamePrefix("league.")
 * @Prefix("/search")
 */
class SearchController extends APIController
{
    /**
     * @Get(path="")
     */
    public function getSearchAction(Request $request): Response
    {
        $query = $request->get('q', null);

        $results = new SearchResult();

        $results->players = $this->get(SearchManager::class)->getSearchPlayers($query);
        $results->accounts = $this->get(SearchManager::class)->getSearchRiotAccounts($query);

        return $this->serialize($results, 'league.get_search');
    }

    /**
     * @Get(path="/players/{name}")
     */
    public function getSearchPlayersAction($name)
    {
        $players = $this->get(SearchManager::class)->getSearchPlayers($name);

        return $this->serialize($players, 'league.get_player');
    }

    /**
     * @Get(path="/teams/{name}")
     */
    public function getSearchTeamsAction($name)
    {
        $players = $this->get(SearchManager::class)->getSearchTeams($name);

        return $this->serialize($players, 'get_teams');
    }
}
