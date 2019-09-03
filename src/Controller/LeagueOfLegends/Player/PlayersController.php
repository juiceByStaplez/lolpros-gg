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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function getPlayersAction(ParamFetcherInterface $paramFetcher, PlayerManager $playerManager): Response
    {
        $players = $playerManager->getList($paramFetcher);

        return $this->serialize($players, 'league.get_players');
    }

    /**
     * @Get(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getPlayerAction(string $uuid): Response
    {
        $player = $this->find(Player::class, $uuid);

        return $this->serialize($player, 'league.get_player');
    }

    /**
     * @Post(path="")
     * @IsGranted("ROLE_ADMIN")
     */
    public function postPlayersAction(PlayerManager $playerManager): Response
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
            return new JsonResponse($this->errorFormatter->reduceForm($form), 422);
        }

        $player = $playerManager->create($player);

        return $this->serialize($player, 'league.get_player', 201);
    }

    /**
     * @Put(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putPlayersAction(string $uuid, PlayerManager $playerManager, ValidatorInterface $validator): Response
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

        $violationList = $validator->validate($playerData, null, ['put_team']);
        if ($violationList->count() > 0) {
            return new JsonResponse($this->errorFormatter->reduce($violationList), 422);
        }

        $player = $playerManager->update($player, $playerData);

        return $this->serialize($player, 'league.get_player');
    }

    /**
     * @Delete(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deletePlayersAction(string $uuid, PlayerManager $playerManager): Response
    {
        $player = $this->find(Player::class, $uuid);

        $playerManager->delete($player);

        return new JsonResponse(null, 204);
    }

    /**
     * @Get(path="/{uuid}/update")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function getPlayerUpdateAction(string $uuid, RiotAccountManager $riotAccountManager): Response
    {
        /** @var Player $player */
        $player = $this->find(Player::class, $uuid);
        $errorCount = 0;
        $accounts = $player->getAccounts();

        foreach ($accounts as $account) {
            try {
                $riotAccountManager->refreshRiotAccount($account);
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
