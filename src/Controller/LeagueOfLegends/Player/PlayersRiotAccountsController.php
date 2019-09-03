<?php

namespace App\Controller\LeagueOfLegends\Player;

use App\Controller\APIController;
use App\Entity\LeagueOfLegends\Player\Player;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/players")
 */
class PlayersRiotAccountsController extends APIController
{
    /**
     * @Get(path="/{uuid}/riot-accounts")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getPlayersRiotAccountsAction(string $uuid): Response
    {
        /* @var Player $player */
        $player = $this->find(Player::class, $uuid);

        return $this->serialize($player->getAccounts(), 'league.get_player_riot_accounts');
    }
}
