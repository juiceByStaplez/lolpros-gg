<?php

namespace App\Provider;

use App\Repository\Core\MemberRepository;
use FOS\ElasticaBundle\Provider\PagerfantaPager;
use FOS\ElasticaBundle\Provider\PagerProviderInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;

class MemberProvider implements PagerProviderInterface
{
    /**
     * @var MemberRepository
     */
    private $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    public function provide(array $options = [])
    {
        $members = $this->memberRepository->findAll();

        return new PagerfantaPager(new Pagerfanta(new ArrayAdapter($members)));
    }
}
