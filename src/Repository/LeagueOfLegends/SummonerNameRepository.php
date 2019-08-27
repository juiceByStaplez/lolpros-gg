<?php

namespace App\Repository\LeagueOfLegends;

use Doctrine\ORM\EntityRepository;

class SummonerNameRepository extends EntityRepository
{
    public function getLatestXChanges(?int $max = 15)
    {
        $result = $this->createQueryBuilder('summonerName')
            ->where('summonerName.previous IS NOT NULL')
            ->orderBy('summonerName.createdAt', 'desc')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
