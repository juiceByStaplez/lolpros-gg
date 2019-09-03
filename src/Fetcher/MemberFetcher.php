<?php

namespace App\Fetcher;

use Elastica\Query;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberFetcher extends Fetcher
{
    const SORT_JOIN = 'join';
    const SORT_LEAVE = 'leave';

    protected function createQuery(array $options): Query
    {
        $query = new Query\BoolQuery();

        if ($options['uuid']) {
            $query->addMust(new Query\MatchPhrase('uuid', $options['uuid']));
        }
        if (null !== $options['current']) {
            $query->addMust(new Query\Term(['current' => $options['current']]));
        }

        $query = new Query($query);

        switch ($options['sort']) {
            case self::SORT_JOIN:
                $query->setSort(['join_date' => ['order' => $options['order']]]);
                break;
            case self::SORT_LEAVE:
                $query->setSort(['leave_date' => ['order' => $options['order']]]);
                break;
            default:
                $query->setSort(['event_date' => ['order' => $options['order']], 'timestamp' => 'desc']);
                break;
        }

        $query->setSize($options['per_page']);
        $query->setFrom(($options['page'] - 1) * $options['per_page']);

        return $query;
    }

    protected function configureOptions(OptionsResolver $resolver): OptionsResolver
    {
        $resolver->setDefaults([
            'per_page' => 50,
            'page' => 1,
            'sort' => null,
            'order' => 'desc',
            'uuid' => null,
            'current' => null,
        ]);

        $resolver->setAllowedTypes('per_page', 'integer');
        $resolver->setAllowedTypes('page', 'integer');
        $resolver->setAllowedTypes('sort', ['string', 'null']);
        $resolver->setAllowedTypes('order', 'string');
        $resolver->setAllowedTypes('uuid', ['string', 'null']);
        $resolver->setAllowedTypes('current', ['boolean', 'null']);

        return $resolver;
    }
}
