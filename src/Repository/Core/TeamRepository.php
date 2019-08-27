<?php

namespace App\Repository\Core;

use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    public function search(string $name): array
    {
        return $this->createQueryBuilder('team')
            ->andWhere('team.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }

    public function getTeamsUuids(): array
    {
        $sql = <<<SQL
SELECT uuid from team__team
SQL;
        $query = $this->getEntityManager()->getConnection()->prepare($sql);
        $query->execute();

        $array = $query->fetchAll();

        $flatten = [];
        array_walk_recursive($array, function ($value) use (&$flatten) {
            $flatten[] = $value;
        });

        return $flatten;
    }
}
