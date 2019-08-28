<?php

namespace App\Model\LeagueOfLegends\Ladder;

use JMS\Serializer\Annotation as Serializer;

class SearchResult
{
    /**
     * @var array
     * @Serializer\Type("array")
     */
    public $players = [];

    /**
     * @var array
     * @Serializer\Type("array")
     */
    public $accounts = [];
}
