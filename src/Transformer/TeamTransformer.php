<?php

namespace App\Transformer;

use App\Entity\Core\Document\Document as Logo;
use App\Entity\Core\Team\Member;
use App\Entity\Core\Team\Team;
use App\Entity\LeagueOfLegends\Player\Player;
use App\Indexer\Indexer;
use Doctrine\Common\Collections\ArrayCollection;
use Elastica\Document;

class TeamTransformer extends DefaultTransformer
{
    public function fetchAndTransform($document, array $fields): ?Document
    {
        $team = $this->entityManager->getRepository(Team::class)->findOneBy(['uuid' => $document['uuid']]);

        if (!$team instanceof Team) {
            return null;
        }

        $document = $this->transform($team, $fields);
        $this->entityManager->clear();

        return $document;
    }

    public function transform($team, array $fields)
    {
        if (!$team instanceof Team) {
            return null;
        }

        $socialMedia = $team->getSocialMedia();
        $region = $team->getRegion();

        $document = [
            'uuid' => $team->getUuidAsString(),
            'name' => $team->getName(),
            'slug' => $team->getSlug(),
            'tag' => $team->getTag(),
            'region' => [
                'uuid' => $region->getUuidAsString(),
                'name' => $region->getName(),
                'slug' => $region->getSlug(),
                'shorthand' => $region->getShorthand(),
                'logo' => $this->buildLogo($region->getLogo()),
            ],
            'logo' => $this->buildLogo($team->getLogo()),
            'active' => (bool) $team->getCurrentMemberships()->count(),
            'creation_date' => $team->getCreationDate()->format(\DateTime::ISO8601),
            'disband_date' => $team->getDisbandDate() ? $team->getDisbandDate()->format(\DateTime::ISO8601) : null,
            'social_media' => [
                'twitter' => $socialMedia->getTwitter(),
                'website' => $socialMedia->getWebsite(),
                'facebook' => $socialMedia->getFacebook(),
                'leaguepedia' => $socialMedia->getLeaguepedia(),
            ],
            'current_members' => $this->buildMembers($team->getCurrentMemberships()),
            'previous_members' => $this->buildMembers($team->getPreviousMemberships()),
        ];

        return new Document($team->getUuidAsString(), $document, Indexer::INDEX_TYPE_TEAM, Indexer::INDEX_TEAMS);
    }

    private function buildMembers(ArrayCollection $memberships)
    {
        if (!$memberships->count()) {
            return null;
        }

        $members = [];

        foreach ($memberships as $membership) {
            /** @var Member $membership */
            /** @var Player $player */
            $player = $membership->getPlayer();
            $ranking = $player->getBestAccount() ? $player->getBestAccount()->getCurrentRanking() : null;

            $members[] = [
                'uuid' => $player->getUuidAsString(),
                'name' => $player->getName(),
                'slug' => $player->getSlug(),
                'current' => $membership->isCurrent(),
                'country' => $player->getCountry(),
                'position' => $player->getPosition(),
                'join_date' => $membership->getJoinDate()->format(\DateTime::ISO8601),
                'join_timestamp' => $membership->getJoinDate()->getTimestamp(),
                'leave_date' => $membership->getLeaveDate() ? $membership->getLeaveDate()->format(\DateTime::ISO8601) : null,
                'leave_timestamp' => $membership->getLeaveDate() ? $membership->getLeaveDate()->getTimestamp() : null,
                'summoner_name' => $player->getMainAccount() ? $player->getMainAccount()->getCurrentSummonerName()->getName() : null,
                'profile_icon_id' => $player->getMainAccount() ? $player->getMainAccount()->getProfileIconId() : null,
                'tier' => $ranking ? $ranking->getTier() : null,
                'rank' => $ranking ? $ranking->getRank() : null,
                'league_points' => $ranking ? $ranking->getLeaguePoints() : null,
                'score' => $ranking ? $ranking->getScore() : null,
            ];
        }

        return $members;
    }

    private function buildLogo(?Logo $logo): ?array
    {
        if (!$logo) {
            return null;
        }

        return [
            'public_id' => $logo->getPublicId(),
            'version' => $logo->getVersion(),
            'url' => $logo->getUrl(),
        ];
    }
}
