<?php

namespace App\Controller\Core\Player;

use App\Controller\APIController;
use App\Entity\Core\Player\Player;
use App\Repository\Core\PlayerRepository;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/players")
 */
class PlayersController extends APIController
{
    /**
     * @Get(path="/countries")
     * @IsGranted("ROLE_USER")
     */
    public function getPlayersCountriesAction(): Response
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $this->getDoctrine()->getRepository(Player::class);

        return new JsonResponse($playerRepository->getCountries(), 200);
    }
}
