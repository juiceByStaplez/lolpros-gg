<?php

namespace App\Provider;

use App\Repository\Core\TeamRepository;
use FOS\ElasticaBundle\Provider\PagerfantaPager;
use FOS\ElasticaBundle\Provider\PagerProviderInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class TeamProvider implements PagerProviderInterface
{
    /**
     * @var TeamRepository
     */
    private $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function provide(array $options = [])
    {
        $players = $this->teamRepository->findAll();

        return new PagerfantaPager(new Pagerfanta(new ArrayAdapter($players)));
    }
}
