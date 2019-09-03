<?php

namespace App\Controller\LeagueOfLegends\Region;

use App\Controller\APIController;
use App\Entity\LeagueOfLegends\Region\Region;
use App\Form\LeagueOfLegends\Region\RegionForm;
use App\Manager\LeagueOfLegends\Region\RegionManager;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/regions")
 */
class RegionsController extends APIController
{
    /**
     * @Get(path="")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function getRegionsAction(): Response
    {
        $regions = $this->getDoctrine()->getRepository(Region::class)->findBy([], ['name' => 'asc']);

        return $this->serialize($regions, 'league.get_regions');
    }

    /**
     * @Get(path="/{uuid}")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function getRegionAction(string $uuid): Response
    {
        $region = $this->find(Region::class, $uuid);

        return $this->serialize($region, 'league.get_region');
    }

    /**
     * @Post(path="")
     * @IsGranted("ROLE_ADMIN")
     */
    public function postRegionsAction(RegionManager $regionManager): Response
    {
        $region = new Region();
        $postedData = $this->getPostedData();

        $form = $this
            ->createForm(
                RegionForm::class,
                $region,
                RegionForm::buildOptions(Request::METHOD_POST)
            )
            ->submit($postedData, false);

        if (!$form->isValid()) {
            return new JsonResponse($this->errorFormatter->reduceForm($form), 422);
        }

        $region = $regionManager->create($region);

        return $this->serialize($region, 'league.get_region', 201);
    }

    /**
     * @Put(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putRegionAction(string $uuid, RegionManager $regionManager): Response
    {
        $region = $this->find(Region::class, $uuid);
        $postedData = $this->getPostedData();

        $form = $this
            ->createForm(
                RegionForm::class,
                $region,
                RegionForm::buildOptions(Request::METHOD_PUT)
            )
            ->submit($postedData, false);

        if (!$form->isValid()) {
            return new JsonResponse($this->errorFormatter->reduceForm($form), 422);
        }

        /* @var Region $region*/
        $region->setCountries($postedData['countries']);

        $region = $regionManager->update($region);

        return $this->serialize($region, 'league.get_region');
    }

    /**
     * @Delete(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteRegionsAction(string $uuid, RegionManager $regionManager): Response
    {
        $region = $this->find(Region::class, $uuid);

        $regionManager->delete($region);

        return new JsonResponse(null, 204);
    }
}
