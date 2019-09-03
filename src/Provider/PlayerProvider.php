<?php

namespace App\Provider;

use App\Repository\LeagueOfLegends\PlayerRepository;
use FOS\ElasticaBundle\Provider\PagerfantaPager;
use FOS\ElasticaBundle\Provider\PagerProviderInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class PlayerProvider implements PagerProviderInterface
{
    /**
     * @var PlayerRepository
     */
    private $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function provide(array $options = [])
    {
        $players = $this->playerRepository->findAll();

        return new PagerfantaPager(new Pagerfanta(new ArrayAdapter($players)));
    }
}
