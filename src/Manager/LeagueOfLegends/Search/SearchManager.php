<?php

namespace App\Manager\LeagueOfLegends\Search;

use App\Entity\Core\Team\Team;
use App\Entity\LeagueOfLegends\Player\Player;
use App\Entity\LeagueOfLegends\Player\RiotAccount;
use App\Manager\DefaultManager;

final class SearchManager extends DefaultManager
{
    public function getSearchPlayers(string $query): array
    {
        return $this->entityManager->getRepository(Player::class)->search($query);
    }

    public function getSearchTeams(string $query): array
    {
        return $this->entityManager->getRepository(Team::class)->search($query);
    }

    public function getSearchRiotAccounts(string $query): array
    {
        return $this->entityManager->getRepository(RiotAccount::class)->search($query);
    }
}
