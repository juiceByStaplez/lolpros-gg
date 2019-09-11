<?php

namespace App\Consumer;

use App\Entity\LeagueOfLegends\Player\SummonerName;
use App\Event\LeagueOfLegends\Player\SummonerNameEvent;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SummonerNamesConsumer implements ConsumerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param AMQPMessage $msg The message
     *
     * @return mixed false to reject and requeue, any other value to acknowledge
     */
    public function execute(AMQPMessage $msg)
    {
        $summoner = $this->entityManager->getRepository(SummonerName::class)->find($msg->body);

        if (!$summoner instanceof SummonerName) {
            $this->logger->error(sprintf('[SummonerNamesConsumer] Could\'t find a summoner name with the id %s', $msg->body));

            return false;
        }

        try {
            $this->eventDispatcher->dispatch(new SummonerNameEvent($summoner), SummonerNameEvent::CREATED);
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
        $this->logger->error(sprintf('[SummonerNamesConsumer] Handled summoner %s (%s)', $msg->body, $summoner->getName()));

        return true;
    }
}
