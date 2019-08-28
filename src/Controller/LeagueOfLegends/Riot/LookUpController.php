<?php

namespace App\Controller\LeagueOfLegends\Riot;

use App\Controller\APIController;
use App\Factory\LeagueOfLegends\LoLProsFactory;
use App\Factory\LeagueOfLegends\RankingsFactory;
use App\Manager\LeagueOfLegends\Player\PlayersManager;
use App\Manager\LeagueOfLegends\Riot\RiotLeagueManager;
use App\Manager\LeagueOfLegends\Riot\RiotSpectatorManager;
use App\Manager\LeagueOfLegends\Riot\RiotSummonerManager;
use FOS\RestBundle\Controller\Annotations\Get;
use RiotAPI\LeagueAPI\Exceptions\RequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/look-up")
 */
class LookUpController extends APIController
{
    /**
     * @Get(path="/summoner/{name}")
     */
    public function getIdFromSummonerNameAction(string $name)
    {
        try {
            $summoner = $this->get(RiotSummonerManager::class)->findPlayer($name);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        return new JsonResponse($summoner);
    }

    /**
     * @Get(path="/id/{id}")
     */
    public function getSummonerNameFromIdAction(string $id)
    {
        try {
            $summoner = $this->get(RiotSummonerManager::class)->getForId($id);
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        return new JsonResponse($summoner);
    }

    /**
     * @Get(path="/game/{name}")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function getGameForSummonerNameAction(string $name)
    {
        try {
            $summoner = $this->get(RiotSummonerManager::class)->findPlayer($name);
        } catch (RequestException $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        try {
            $game = $this->get(RiotSpectatorManager::class)->findGame($summoner->id);
        } catch (RequestException $e) {
            return new JsonResponse($e->getMessage(), 406);
        }

        foreach ($game->participants as $participant) {
            $soloQ = $this->get(RiotLeagueManager::class)->getForId($participant->summonerId);
            $participant->ranking = $soloQ ? RankingsFactory::createArrayFromLeague($soloQ) : RankingsFactory::createEmptyArray();

            $lolpros = $this->get(PlayersManager::class)->findWithAccount($participant->summonerId);
            $participant->lolpros = $lolpros ? LoLProsFactory::createArrayFromRiotAccount($lolpros) : null;
        }

        return new JsonResponse($game);
    }
}
