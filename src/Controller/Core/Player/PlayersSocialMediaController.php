<?php

namespace App\Controller\Core\Player;

use App\Controller\APIController;
use App\Entity\Core\Player\Player;
use App\Entity\Core\Player\SocialMedia;
use App\Manager\Core\Player\SocialMediaManager;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\Controller\Annotations\Put;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @NamePrefix("app.")
 * @Prefix("/players")
 */
class PlayersSocialMediaController extends APIController
{
    /**
     * @Get(path="/{uuid}/social-medias")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getPlayerSocialMediasAction(string $uuid): Response
    {
        /** @var Player $player */
        $player = $this->find(Player::class, $uuid);

        return $this->serialize($player->getSocialMedia(), 'get_player_social_medias');
    }

    /**
     * @Put(path="/{uuid}/social-medias")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putPlayerSocialMediasAction(string $uuid): Response
    {
        /** @var Player $player */
        $player = $this->find(Player::class, $uuid);
        $socialMedia = $this->deserialize(SocialMedia::class, 'post_player_social_medias');

        $violationList = $this->get('validator')->validate($socialMedia, null, ['post_player_social_medias']);
        if ($violationList->count() > 0) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduce($violationList), 422);
        }

        $socialMedia = $this->get(SocialMediaManager::class)->updateSocialMedia($player, $socialMedia);

        return $this->serialize($socialMedia, 'get_player_social_medias');
    }
}
