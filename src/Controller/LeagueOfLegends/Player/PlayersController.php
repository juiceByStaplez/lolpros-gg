<?php

namespace App\Controller\LeagueOfLegends\Player;

use App\Controller\APIController;
use App\Entity\LeagueOfLegends\Player\Player;
use App\Entity\LeagueOfLegends\Region\Region;
use App\Exception\LeagueOfLegends\AccountRecentlyUpdatedException;
use App\Form\LeagueOfLegends\Player\PlayerForm;
use App\Manager\LeagueOfLegends\Player\PlayerManager;
use App\Manager\LeagueOfLegends\Player\RiotAccountManager;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/players")
 */
class PlayersController extends APIController
{
    /**
     * @Get(path="")
     * @IsGranted("ROLE_ADMIN")
     * @QueryParam(name="position", default=null, nullable=true)
     */
    public function getPlayersAction(ParamFetcherInterface $paramFetcher): Response
    {
        $players = $this->get(PlayerManager::class)->getList($paramFetcher);

        return $this->serialize($players, 'league.get_players');
    }

    /**
     * @Get(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getPlayerAction($uuid): Response
    {
        $player = $this->find(Player::class, $uuid);

        return $this->serialize($player, 'league.get_player');
    }

    /**
     * @Post(path="")
     * @IsGranted("ROLE_ADMIN")
     */
    public function postPlayersAction()
    {
        $player = new Player();
        $postedData = $this->getPostedData();

        $regions = new ArrayCollection();
        foreach ($postedData['regions'] as $region) {
            $regions->add($this->find(Region::class, $region));
        }
        $player->setRegions($regions);
        unset($postedData['regions']);

        $form = $this
            ->createForm(PlayerForm::class, $player, PlayerForm::buildOptions(Request::METHOD_POST, $postedData))
            ->submit($postedData, false);

        if (!$form->isValid()) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduceForm($form), 422);
        }

        $player = $this->get(PlayerManager::class)->create($player);

        return $this->serialize($player, 'league.get_player', 201);
    }

    /**
     * @Put(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putPlayersAction($uuid)
    {
        /** @var Player $player */
        $player = $this->find(Player::class, $uuid);
        $postedData = $this->getPostedData();

        $playerData = $this->deserialize(Player::class, 'league.put_player');
        $regions = new ArrayCollection();
        foreach ($postedData['regions'] as $region) {
            $regions->add($this->find(Region::class, is_array($region) ? $region['uuid'] : $region));
        }
        $playerData->setRegions($regions);

        $violationList = $this->get('validator')->validate($playerData, null, ['put_team']);
        if ($violationList->count() > 0) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduce($violationList), 422);
        }

        $player = $this->get(PlayerManager::class)->update($player, $playerData);

        return $this->serialize($player, 'league.get_player');
    }

    /**
     * @Delete(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deletePlayersAction($uuid)
    {
        $player = $this->find(Player::class, $uuid);

        $this->get(PlayerManager::class)->delete($player);

        return new JsonResponse(null, 204);
    }

    /**
     * @Get(path="/{uuid}/update")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function getPlayerUpdateAction($uuid)
    {
        /** @var Player $player */
        $player = $this->find(Player::class, $uuid);
        $errorCount = 0;
        $accounts = $player->getAccounts();

        foreach ($accounts as $account) {
            try {
                $this->get(RiotAccountManager::class)->refreshRiotAccount($account);
            } catch (AccountRecentlyUpdatedException $e) {
                ++$errorCount;
            }
        }

        if ($errorCount && $errorCount === $accounts->count()) {
            return new JsonResponse(null, 409);
        }

        return new JsonResponse(null, 200);
    }
}
