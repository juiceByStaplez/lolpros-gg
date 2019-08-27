<?php

namespace App\Factory\LeagueOfLegends;

use App\Entity\LeagueOfLegends\Player\Ranking;
use App\Manager\LeagueOfLegends\Player\RankingsManager;
use RiotAPI\LeagueAPI\Objects\LeagueEntryDto;

class RankingsFactory
{
    public static function createFromLeague(LeagueEntryDto $league): Ranking
    {
        $ranking = new Ranking();

        $ranking->setQueueType($league->queueType)
            ->setTier(RankingsManager::tierToDatabase($league->tier))
            ->setRank(RankingsManager::rankToDatabase($league->rank))
            ->setWins($league->wins)
            ->setLosses($league->losses)
            ->setLeaguePoints($league->leaguePoints)
            ->setScore(RankingsManager::calculateScore($ranking))
            ->setSeason(Ranking::SEASON_9_V2);

        return $ranking;
    }

    public static function createEmptyRanking(): Ranking
    {
        $ranking = new Ranking();

        $ranking->setQueueType(Ranking::QUEUE_TYPE_SOLO)
            ->setTier(Ranking::TIER_UNRANKED)
            ->setWins(0)
            ->setLosses(0)
            ->setLeaguePoints(0)
            ->setScore(0)
            ->setSeason(Ranking::SEASON_9_V2);

        return $ranking;
    }

    public static function createArrayFromLeague(LeagueEntryDto $league): array
    {
        return [
            'queueType' => $league->queueType,
            'tier' => RankingsManager::tierToDatabase($league->tier),
            'rank' => RankingsManager::rankToDatabase($league->rank),
            'wins' => $league->wins,
            'losses' => $league->losses,
            'leaguePoints' => $league->leaguePoints,
            'season' => Ranking::SEASON_9_V2,
            'miniSeries' => $league->miniSeries ? [
                'wins' => $league->miniSeries->wins,
                'losses' => $league->miniSeries->losses,
                'target' => $league->miniSeries->target,
                'progress' => $league->miniSeries->progress,
            ] : null,
        ];
    }

    public static function createEmptyArray(): array
    {
        return [
            'queueType' => Ranking::QUEUE_TYPE_SOLO,
            'tier' => Ranking::TIER_UNRANKED,
            'rank' => 0,
            'wins' => 0,
            'losses' => 0,
            'leaguePoints' => 0,
            'season' => Ranking::SEASON_9_V2,
        ];
    }
}
