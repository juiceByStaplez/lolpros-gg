<?php

namespace App\Listener\Core;

use App\Entity\Core\Region\Region;
use App\Event\Core\Region\RegionEvent;
use App\Indexer\Indexer;
use App\Manager\Core\Report\AdminLogManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegionListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AdminLogManager
     */
    private $adminLogManager;

    /**
     * @var Indexer
     */
    private $playerIndexer;

    /**
     * @var Indexer
     */
    private $ladderIndexer;

    /**
     * @var Indexer
     */
    private $teamIndexer;

    public static function getSubscribedEvents()
    {
        return [
            RegionEvent::CREATED => 'onCreate',
            RegionEvent::UPDATED => 'onUpdate',
            RegionEvent::DELETED => 'onDelete',
        ];
    }

    public function __construct(LoggerInterface $logger, AdminLogManager $adminLogManager, Indexer $playerIndexer, Indexer $ladderIndexer, Indexer $teamIndexer)
    {
        $this->logger = $logger;
        $this->adminLogManager = $adminLogManager;
        $this->playerIndexer = $playerIndexer;
        $this->ladderIndexer = $ladderIndexer;
        $this->teamIndexer = $teamIndexer;
    }

    private function updateLinkedEntities(Region $region)
    {
        foreach ($region->getPlayers() as $player) {
            $this->playerIndexer->updateOne(Indexer::INDEX_TYPE_PLAYER, $player);
            $this->ladderIndexer->updateOne(Indexer::INDEX_TYPE_LADDER, $player);
        }
        foreach ($region->getTeams() as $team) {
            $this->teamIndexer->updateOne(Indexer::INDEX_TYPE_TEAM, $team);
        }
    }

    public function onCreate(RegionEvent $event)
    {
        $entity = $event->getRegion();

        if (!$entity instanceof Region) {
            return;
        }
        $this->adminLogManager->createLog(RegionEvent::CREATED, $entity->getUuidAsString(), $entity->getName());
    }

    public function onUpdate(RegionEvent $event)
    {
        $entity = $event->getRegion();

        if (!$entity instanceof Region) {
            return;
        }

        $this->updateLinkedEntities($entity);
        $this->adminLogManager->createLog(RegionEvent::UPDATED, $entity->getUuidAsString(), $entity->getName());
    }

    public function onDelete(RegionEvent $event)
    {
        $entity = $event->getRegion();

        if (!$entity instanceof Region) {
            return;
        }

        $this->updateLinkedEntities($entity);
        $this->adminLogManager->createLog(RegionEvent::DELETED, $entity->getUuidAsString(), $entity->getName());
    }
}
