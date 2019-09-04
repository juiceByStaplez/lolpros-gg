<?php

namespace App\Manager\Core\Player;

use App\Entity\Core\Player\Player;
use App\Entity\Core\Player\SocialMedia;
use App\Event\LeagueOfLegends\Player\PlayerEvent;
use App\Exception\Core\EntityNotUpdatedException;
use App\Manager\DefaultManager;
use Exception;

final class SocialMediaManager extends DefaultManager
{
    public function updateSocialMedia(Player $player, SocialMedia $socialMedia): SocialMedia
    {
        try {
            $media = $player->getSocialMedia();

            $media->setFacebook($socialMedia->getFacebook());
            $media->setTwitch($socialMedia->getTwitch());
            $media->setDiscord($socialMedia->getDiscord());
            $media->setTwitter($socialMedia->getTwitter());
            $media->setLeaguepedia($socialMedia->getLeaguepedia());

            $this->entityManager->flush($media);
            $this->entityManager->flush($player);

            if ($player instanceof \App\Entity\LeagueOfLegends\Player\Player) {
                $this->eventDispatcher->dispatch(new PlayerEvent($player), PlayerEvent::UPDATED);
            }

            return $media;
        } catch (Exception $e) {
            $this->logger->error('[SocialMediaManager] Could not update social medias for player {uuid} because of {reason}', ['uuid' => $player->getUuidAsString(), 'reason' => $e->getMessage()]);
            throw new EntityNotUpdatedException($socialMedia->getOwner()->getUuidAsString(), $e->getMessage());
        }
    }
}
