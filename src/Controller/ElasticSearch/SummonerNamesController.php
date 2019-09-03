<?php

namespace App\Controller\ElasticSearch;

use App\Controller\APIController;
use App\Fetcher\SummonerNameFetcher;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @NamePrefix("es.")
 */
class SummonerNamesController extends APIController
{
    /**
     * @Get(path="/summoner-names")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @QueryParam(name="page", default=1, nullable=true)
     * @QueryParam(name="per_page", default=50, nullable=true)
     * @QueryParam(name="previous", nullable=true)
     */
    public function getSummonerNamesAction(ParamFetcher $paramFetcher, SummonerNameFetcher $summonerNamesFetcher): JsonResponse
    {
        $options = [
            'previous' => $this->getNullOrBoolean($paramFetcher->get('previous')),
        ];

        if ($paramFetcher->get('page')) {
            $options['page'] = (int) $paramFetcher->get('page');
        }
        if ($paramFetcher->get('per_page')) {
            $options['per_page'] = (int) $paramFetcher->get('per_page');
        }
        $summonerNames = $summonerNamesFetcher->fetchByPage($options);

        return new JsonResponse($summonerNames);
    }
}
