<?php

namespace App\Listener\LeagueOfLegends;

use App\Entity\Core\Team\Member;
use App\Entity\LeagueOfLegends\Player\Player;
use App\Event\LeagueOfLegends\Player\PlayerEvent;
use App\Indexer\Indexer;
use App\Manager\Core\Report\AdminLogManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PlayerListener implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var AdminLogManager
     */
    protected $adminLogManager;

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
            PlayerEvent::CREATED => 'onCreate',
            PlayerEvent::UPDATED => 'onUpdate',
            PlayerEvent::DELETED => 'onDelete',
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

    public function onCreate(PlayerEvent $event)
    {
        $entity = $event->getPlayer();

        if (!$entity instanceof Player) {
            return;
        }

        $this->playerIndexer->addOne(Indexer::INDEX_TYPE_PLAYER, $entity);
        $this->ladderIndexer->addOne(Indexer::INDEX_TYPE_LADDER, $entity);
        $this->adminLogManager->createLog(PlayerEvent::CREATED, $entity->getUuidAsString(), $entity->getName());
    }

    public function onUpdate(PlayerEvent $event)
    {
        $entity = $event->getPlayer();

        if (!$entity instanceof Player) {
            return;
        }

        $this->playerIndexer->updateOne(Indexer::INDEX_TYPE_PLAYER, $entity);
        $this->ladderIndexer->updateOne(Indexer::INDEX_TYPE_LADDER, $entity);
        foreach ($entity->getMemberships() as $membership) {
            /* @var Member $membership */
            $this->teamIndexer->updateOne(Indexer::INDEX_TYPE_TEAM, $membership->getTeam());
        }
        $this->adminLogManager->createLog(PlayerEvent::UPDATED, $entity->getUuidAsString(), $entity->getName());
    }

    public function onDelete(PlayerEvent $event)
    {
        $entity = $event->getPlayer();

        if (!$entity instanceof Player) {
            return;
        }

        $this->playerIndexer->deleteOne(Indexer::INDEX_TYPE_PLAYER, $entity->getUuidAsString());
        $this->ladderIndexer->deleteOne(Indexer::INDEX_TYPE_LADDER, $entity->getUuidAsString());
        foreach ($entity->getMemberships() as $membership) {
            /* @var Member $membership */
            $this->teamIndexer->updateOne(Indexer::INDEX_TYPE_TEAM, $membership->getTeam());
        }
        $this->adminLogManager->createLog(PlayerEvent::DELETED, $entity->getUuidAsString(), $entity->getName());
    }
}
