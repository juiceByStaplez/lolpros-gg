<?php

namespace App\Controller\LeagueOfLegends\Player;

use App\Controller\APIController;
use App\Entity\LeagueOfLegends\Player\Player;
use App\Entity\LeagueOfLegends\Player\RiotAccount;
use App\Exception\Core\EntityNotDeletedException;
use App\Exception\LeagueOfLegends\AccountRecentlyUpdatedException;
use App\Form\LeagueOfLegends\Player\RiotAccountForm;
use App\Manager\LeagueOfLegends\Player\RiotAccountManager;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/riot-accounts")
 */
class RiotAccountsController extends APIController
{
    /**
     * @Get(path="")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getRiotAccountsAction()
    {
        $accounts = $this->getDoctrine()->getRepository(RiotAccount::class)->findAll();

        return $this->serialize($accounts, 'league.get_riot_accounts');
    }

    /**
     * @Get(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getRiotAccountAction($uuid)
    {
        $account = $this->find(RiotAccount::class, $uuid);

        return $this->serialize($account, 'league.get_riot_account');
    }

    /**
     * @Post(path="")
     * @IsGranted("ROLE_ADMIN")
     */
    public function postRiotAccountsAction(Request $request)
    {
        $content = json_decode($request->getContent());
        $datas = $this->deserialize(RiotAccount::class, 'league.post_riot_account');
        $player = $this->find(Player::class, $content->player);

        $riotAccount = $this->get(RiotAccountManager::class)->createRiotAccount($datas, $player);

        return $this->serialize($riotAccount, 'league.get_riot_account', 201);
    }

    /**
     * @Put(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putRiotAccountAction($uuid)
    {
        $riotAccount = $this->find(RiotAccount::class, $uuid);
        $postedData = $this->getPostedData();

        $form = $this
            ->createForm(RiotAccountForm::class, $riotAccount, RiotAccountForm::buildOptions(Request::METHOD_PUT, $postedData))
            ->submit($postedData, false);

        if (!$form->isValid()) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduceForm($form), 422);
        }

        $riotAccount = $this->get(RiotAccountManager::class)->update($riotAccount);

        return $this->serialize($riotAccount, 'league.get_riot_account');
    }

    /**
     * @Put(path="/{uuid}/update")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putRiotAccountRefreshAction($uuid)
    {
        $riotAccount = $this->find(RiotAccount::class, $uuid);
        try {
            $riotAccount = $this->get(RiotAccountManager::class)->refreshRiotAccount($riotAccount);
        } catch (AccountRecentlyUpdatedException $e) {
            return new JsonResponse($e->getMessage(), 409);
        }

        return $this->serialize($riotAccount, 'league.get_riot_account');
    }

    /**
     * @Delete(path="/{uuid}")
     */
    public function deleteRiotAccountAction($uuid)
    {
        $riotAccount = $this->find(RiotAccount::class, $uuid);
        try {
            $this->get(RiotAccountManager::class)->delete($riotAccount);
        } catch (EntityNotDeletedException $e) {
            return new JsonResponse($e->getMessage(), 409);
        }

        return new JsonResponse(null, 204);
    }
}
