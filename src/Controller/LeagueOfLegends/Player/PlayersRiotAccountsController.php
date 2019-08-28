<?php

namespace App\Controller\LeagueOfLegends\Player;

use App\Controller\APIController;
use App\Entity\LeagueOfLegends\Player\Player;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Prefix;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @NamePrefix("league.")
 * @Prefix("/players")
 */
class PlayersRiotAccountsController extends APIController
{
    /**
     * @Get(path="/{uuid}/riot-accounts")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getPlayersRiotAccountsAction($uuid)
    {
        /* @var Player $player */
        $player = $this->find(Player::class, $uuid);

        return $this->serialize($player->getAccounts(), 'league.get_player_riot_accounts');
    }
}
