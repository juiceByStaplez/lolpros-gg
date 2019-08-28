<?php

namespace App\Controller\LeagueOfLegends\Region;

use App\Controller\APIController;
use App\Entity\LeagueOfLegends\Region\Region;
use App\Form\LeagueOfLegends\Region\RegionForm;
use App\Manager\LeagueOfLegends\Region\RegionsManager;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function getRegionsAction()
    {
        $regions = $this->getDoctrine()->getRepository(Region::class)->findBy([], ['name' => 'asc']);

        return $this->serialize($regions, 'league.get_regions');
    }

    /**
     * @Get(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getRegionAction($uuid)
    {
        $region = $this->find(Region::class, $uuid);

        return $this->serialize($region, 'league.get_region');
    }

    /**
     * @Post(path="")
     * @IsGranted("ROLE_ADMIN")
     */
    public function postRegionsAction()
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
            return new JsonResponse($this->get('service.generic.error_formatter')->reduceForm($form), 422);
        }

        $region = $this->get(RegionsManager::class)->create($region);

        return $this->serialize($region, 'league.get_region', 201);
    }

    /**
     * @Put(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putRegionAction($uuid)
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
            return new JsonResponse($this->get('service.generic.error_formatter')->reduceForm($form), 422);
        }

        /* @var Region $region*/
        $region->setCountries($postedData['countries']);

        $region = $this->get(RegionsManager::class)->update($region);

        return $this->serialize($region, 'league.get_region');
    }

    /**
     * @Delete(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteRegionsAction($uuid)
    {
        $region = $this->find(Region::class, $uuid);

        $this->get(RegionsManager::class)->delete($region);

        return new JsonResponse(null, 204);
    }
}
