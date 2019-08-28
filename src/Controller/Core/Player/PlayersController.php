<?php

namespace App\Controller\Core\Player;

use App\Controller\APIController;
use App\Entity\LeagueOfLegends\Player\Player;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Prefix;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @NamePrefix("app.")
 * @Prefix("/players")
 */
class PlayersController extends APIController
{
    /**
     * @Get(path="/countries")
     * @IsGranted("ROLE_USER")
     */
    public function getPlayersCountriesAction(): Response
    {
        $countries = $this->getDoctrine()->getRepository(Player::class)->getCountries();

        return new JsonResponse($countries, 200);
    }
}