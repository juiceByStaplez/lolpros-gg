<?php

namespace App\Controller\LeagueOfLegends\Riot;

use App\Controller\APIController;
use App\Exception\LeagueOfLegends\AccountAlreadyExistsException;
use App\Manager\LeagueOfLegends\Player\RiotAccountsManager;
use App\Manager\LeagueOfLegends\Riot\RiotLeagueManager;
use App\Manager\LeagueOfLegends\Riot\RiotSummonerManager;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Prefix;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @NamePrefix("league.")
 * @Prefix("/riot")
 */
class RiotController extends APIController
{
    /**
     * @Get("/summoner/{name}")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function getRiotPlayerSearchAction(string $name): Response
    {
        try {
            $summoner = $this->get(RiotSummonerManager::class)->findPlayer($name);

            if ($this->get(RiotAccountsManager::class)->accountExists($summoner->id)) {
                throw new AccountAlreadyExistsException();
            }

            $summoner->leagues = $this->get(RiotLeagueManager::class)->getForId($summoner->id);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        return new JsonResponse($summoner);
    }
}
