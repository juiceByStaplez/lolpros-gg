<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

abstract class DefaultManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }
}
