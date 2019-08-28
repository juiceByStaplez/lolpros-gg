<?php

namespace App\Controller\LeagueOfLegends\Player;

use App\Controller\APIController;
use App\Entity\LeagueOfLegends\Player\RiotAccount;
use App\Manager\LeagueOfLegends\Player\RankingsManager;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

/**
 * @NamePrefix("league.")
 * @Prefix("/riot-accounts")
 */
class RiotAccountsRankingsController extends APIController
{
    /**
     * @Get(path="/{uuid}/rankings")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @QueryParam(name="months", nullable=true)
     */
    public function getRiotAccountsRankingsAction($uuid, Request $request)
    {
        /* @var RiotAccount $riotAccount */
        $riotAccount = $this->find(RiotAccount::class, $uuid);
        $rankings = $this->get(RankingsManager::class)->getRankingsForRiotAccount($riotAccount, $request->get('months', 1));

        return $this->serialize($rankings, 'league.get_riot_account_rankings');
    }
}
