<?php

namespace App\Controller\ElasticSearch;

use App\Controller\APIController;
use App\Fetcher\MemberFetcher;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @NamePrefix("es.")
 */
class MembersController extends APIController
{
    /**
     * @Get(path="/members")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     * @QueryParam(name="page", default=1, nullable=true)
     * @QueryParam(name="per_page", default=50, nullable=true)
     * @QueryParam(name="current", nullable=true)
     * @QueryParam(name="sort", nullable=true)
     * @QueryParam(name="order", nullable=true)
     */
    public function getMembersAction(ParamFetcherInterface $paramFetcher, MemberFetcher $membersFetcher): JsonResponse
    {
        $options = [
            'current' => $this->getNullOrBoolean($paramFetcher->get('current')),
            'sort' => $paramFetcher->get('sort'),
        ];

        if ($paramFetcher->get('page')) {
            $options['page'] = (int) $paramFetcher->get('page');
        }
        if ($paramFetcher->get('per_page')) {
            $options['per_page'] = (int) $paramFetcher->get('per_page');
        }
        if ($paramFetcher->get('order')) {
            $options['order'] = $paramFetcher->get('order');
        }

        $members = $membersFetcher->fetchByPage($options);

        return new JsonResponse($members);
    }

    /**
     * @Get(path="/members/{uuid}", requirements={"uuid"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function getTeamUuidAction(string $uuid, MemberFetcher $membersFetcher): JsonResponse
    {
        $member = $membersFetcher->fetchOne(['uuid' => $uuid]);

        if (!$member) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse($member);
    }
}
