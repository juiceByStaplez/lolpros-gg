<?php

namespace App\Fetcher;

use Elastica\Query;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SummonerNameFetcher extends Fetcher
{
    protected function createQuery(array $options): Query
    {
        $query = new Query\BoolQuery();

        if (null !== $options['previous']) {
            $options['previous'] ? $query->addMust(new Query\Exists('previous')) : $query->addMustNot(new Query\Exists('previous'));
        }

        $query = new Query($query);

        $query->setSize($options['per_page']);
        $query->setFrom(($options['page'] - 1) * $options['per_page']);
        $query->setSort(['created_at' => 'desc']);

        return $query;
    }

    protected function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        $resolver->setDefaults([
            'per_page' => 15,
            'page' => 1,
            'previous' => null,
        ]);

        $resolver->setAllowedTypes('per_page', 'integer');
        $resolver->setAllowedTypes('page', 'integer');
        $resolver->setAllowedTypes('previous', ['bool', 'null']);

        return $resolver;
    }
}
