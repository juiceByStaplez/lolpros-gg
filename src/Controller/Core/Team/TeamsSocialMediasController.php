<?php

namespace App\Controller\Core\Team;

use App\Controller\APIController;
use App\Entity\Core\Team\SocialMedia;
use App\Entity\Core\Team\Team;
use App\Manager\Core\Team\SocialMediaManager;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teams")
 */
class TeamsSocialMediasController extends APIController
{
    /**
     * @Get(path="/{uuid}/social-medias")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getTeamSocialMediasAction(string $uuid): Response
    {
        /** @var Team $team */
        $team = $this->find(Team::class, $uuid);

        return $this->serialize($team->getSocialMedia(), 'get_team_social_medias');
    }

    /**
     * @Put(path="/{uuid}/social-medias")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putTeamSocialMediasAction(string $uuid): Response
    {
        /** @var Team $team */
        $team = $this->find(Team::class, $uuid);
        $socialMedia = $this->deserialize(SocialMedia::class, 'put_team_social_medias');

        $violationList = $this->get('validator')->validate($socialMedia, null, ['put_team_social_medias']);
        if ($violationList->count() > 0) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduce($violationList), 422);
        }

        $socialMedia = $this->get(SocialMediaManager::class)->updateSocialMedia($team, $socialMedia);

        return $this->serialize($socialMedia, 'get_team_social_medias');
    }
}
