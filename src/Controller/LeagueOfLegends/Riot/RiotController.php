<?php

namespace App\Controller\LeagueOfLegends\Riot;

use App\Controller\APIController;
use App\Exception\LeagueOfLegends\AccountAlreadyExistsException;
use App\Manager\LeagueOfLegends\Player\RiotAccountManager;
use App\Manager\LeagueOfLegends\Riot\RiotLeagueManager;
use App\Manager\LeagueOfLegends\Riot\RiotSummonerManager;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/riot")
 */
class RiotController extends APIController
{
    /**
     * @Get("/summoner/{name}")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function getRiotPlayerSearchAction(
        string $name,
        RiotSummonerManager $riotSummonerManager,
        RiotAccountManager $riotAccountManager,
        RiotLeagueManager $riotLeagueManager
    ): Response {
        try {
            $summoner = $riotSummonerManager->findPlayer($name);

            if ($riotAccountManager->accountExists($summoner->id)) {
                throw new AccountAlreadyExistsException();
            }

            $summoner->leagues = $riotLeagueManager->getForId($summoner->id);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        return new JsonResponse($summoner);
    }
}
