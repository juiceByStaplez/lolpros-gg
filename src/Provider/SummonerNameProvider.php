<?php

namespace App\Provider;

use App\Repository\LeagueOfLegends\SummonerNameRepository;
use FOS\ElasticaBundle\Provider\PagerfantaPager;
use FOS\ElasticaBundle\Provider\PagerProviderInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class SummonerNameProvider implements PagerProviderInterface
{
    /**
     * @var SummonerNameRepository
     */
    private $summonerNameRepository;

    public function __construct(SummonerNameRepository $summonerNameRepository)
    {
        $this->summonerNameRepository = $summonerNameRepository;
    }

    public function provide(array $options = [])
    {
        $players = $this->summonerNameRepository->findAll();

        return new PagerfantaPager(new Pagerfanta(new ArrayAdapter($players)));
    }
}
