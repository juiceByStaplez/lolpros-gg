<?php

namespace App\Controller\Core\Player;

use App\Controller\APIController;
use App\Entity\Core\Player\Player;
use App\Entity\Core\Player\SocialMedia;
use App\Manager\Core\Player\SocialMediaManager;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Put;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/players")
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
    public function putPlayerSocialMediasAction(string $uuid, ValidatorInterface $validator, SocialMediaManager $socialMediaManager): Response
    {
        /** @var Player $player */
        $player = $this->find(Player::class, $uuid);
        $socialMedia = $this->deserialize(SocialMedia::class, 'put_player_social_medias');

        $violationList = $validator->validate($socialMedia, null, ['put_player_social_medias']);
        if ($violationList->count() > 0) {
            return new JsonResponse($this->errorFormatter->reduce($violationList), 422);
        }

        $socialMedia = $socialMediaManager->updateSocialMedia($player, $socialMedia);

        return $this->serialize($socialMedia, 'get_player_social_medias');
    }
}
