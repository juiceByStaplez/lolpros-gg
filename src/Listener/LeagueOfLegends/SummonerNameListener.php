<?php

namespace App\Listener\LeagueOfLegends;

use App\Entity\LeagueOfLegends\Player\SummonerName;
use App\Event\LeagueOfLegends\Player\SummonerNameEvent;
use App\Indexer\Indexer;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SummonerNameListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Indexer
     */
    private $playerIndexer;

    /**
     * @var Indexer
     */
    private $summonerNameIndexer;

    public static function getSubscribedEvents()
    {
        return [
            SummonerNameEvent::CREATED => 'onCreate',
        ];
    }

    public function __construct(LoggerInterface $logger, Indexer $playerIndexer, Indexer $summonerNameIndexer)
    {
        $this->logger = $logger;
        $this->playerIndexer = $playerIndexer;
        $this->summonerNameIndexer = $summonerNameIndexer;
    }

    public function onCreate(SummonerNameEvent $event)
    {
        $entity = $event->getSummonerName();

        if (!$entity instanceof SummonerName) {
            return;
        }

        $this->summonerNameIndexer->addOne(Indexer::INDEX_TYPE_SUMMONER_NAME, $entity);
        $this->playerIndexer->updateOne(Indexer::INDEX_TYPE_PLAYER, $entity->getOwner()->getPlayer());
        if ($previous = $entity->getPrevious()) {
            $this->summonerNameIndexer->updateOne(Indexer::INDEX_TYPE_SUMMONER_NAME, $previous);
        }
    }
}
