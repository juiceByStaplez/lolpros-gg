<?php

namespace App\Controller\LeagueOfLegends\Search;

use App\Controller\APIController;
use App\Manager\LeagueOfLegends\Search\SearchManager;
use App\Model\LeagueOfLegends\Ladder\SearchResult;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/search")
 */
class SearchController extends APIController
{
    /**
     * @Get(path="")
     */
    public function getSearchAction(Request $request, SearchManager $searchManager): Response
    {
        $query = $request->get('q', null);

        $results = new SearchResult();
        $results->players = $searchManager->getSearchPlayers($query);
        $results->accounts = $searchManager->getSearchRiotAccounts($query);

        return $this->serialize($results, 'league.get_search');
    }

    /**
     * @Get(path="/players/{name}")
     */
    public function getSearchPlayersAction(string $name, SearchManager $searchManager): Response
    {
        $players = $searchManager->getSearchPlayers($name);

        return $this->serialize($players, 'league.get_player');
    }

    /**
     * @Get(path="/teams/{name}")
     */
    public function getSearchTeamsAction(string $name, SearchManager $searchManager): Response
    {
        $players = $searchManager->getSearchTeams($name);

        return $this->serialize($players, 'get_teams');
    }
}
