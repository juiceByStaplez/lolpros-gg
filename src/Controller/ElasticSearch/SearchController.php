<?php

namespace App\Controller\ElasticSearch;

use App\Controller\APIController;
use App\Fetcher\SearchFetcher;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @NamePrefix("es.")
 */
class SearchController extends APIController
{
    /**
     * @Get(path="/search/{query}")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function getSearchAction(string $query, SearchFetcher $searchFetcher): JsonResponse
    {
        $results = $searchFetcher->fetchByPage(['query' => $query]);

        return new JsonResponse($results);
    }
}
