<?php

namespace App\Controller\LeagueOfLegends\Player;

use App\Controller\APIController;
use App\Entity\LeagueOfLegends\Player\Player;
use App\Entity\LeagueOfLegends\Player\RiotAccount;
use App\Manager\LeagueOfLegends\Player\RiotAccountManager;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
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

    /**
     * @Post(path="/{uuid}/riot-accounts")
     * @IsGranted("ROLE_ADMIN")
     */
    public function postPlayerRiotAccountAction(string $uuid, RiotAccountManager $riotAccountManager): Response
    {
        /* @var Player $player */
        $player = $this->find(Player::class, $uuid);
        $riotAccount = $this->deserialize(RiotAccount::class, 'league.post_riot_account');
        $riotAccount = $riotAccountManager->createRiotAccount($riotAccount, $player);

        return $this->serialize($riotAccount, 'league.get_riot_account', 201);
    }
}
