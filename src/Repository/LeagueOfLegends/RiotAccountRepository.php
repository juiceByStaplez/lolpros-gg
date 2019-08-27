<?php

namespace App\Repository\LeagueOfLegends;

use App\Entity\LeagueOfLegends\Player\Player;
use App\Entity\LeagueOfLegends\Player\RiotAccount;
use Doctrine\ORM\EntityRepository;

class RiotAccountRepository extends EntityRepository
{
    public function search(string $name): array
    {
        return $this->createQueryBuilder('riotAccount')
            ->join('riotAccount.summonerNames', 'summonerName')
            ->andWhere('summonerName.name LIKE :name')
            ->andWhere('summonerName.current = 1')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }

    public function getCurrentBestForPlayer(Player $player): ?RiotAccount
    {
        $result = $this->createQueryBuilder('riotAccount')
            ->join('riotAccount.summonerNames', 'summonerNames')
            ->orderBy('riotAccount.score', 'desc')
            ->where('riotAccount.player = :player')
            ->setParameter('player', $player)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return $result[0];
    }
}
