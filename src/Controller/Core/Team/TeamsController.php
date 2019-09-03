<?php

namespace App\Controller\Core\Team;

use App\Controller\APIController;
use App\Entity\Core\Team\Team;
use App\Entity\LeagueOfLegends\Region\Region;
use App\Form\Core\Team\TeamForm;
use App\Manager\Core\Team\TeamManager;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teams")
 */
class TeamsController extends APIController
{
    /**
     * @Get(path="")
     * @QueryParam(name="active", nullable=true)
     * @IsGranted("ROLE_ADMIN")
     */
    public function getTeamsAction(ParamFetcher $paramFetcher): Response
    {
        $options = [];

        if (($active = $this->getNullOrBoolean($paramFetcher->get('active')))) {
            $options['active'] = $active;
        }

        $teams = $this->getDoctrine()->getRepository(Team::class)->findBy($options, ['name' => 'asc']);

        return $this->serialize($teams, 'get_teams');
    }

    /**
     * @Get(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getTeamAction(string $uuid): Response
    {
        $team = $this->find(Team::class, $uuid);

        return $this->serialize($team, 'get_team');
    }

    /**
     * @Post(path="")
     * @IsGranted("ROLE_ADMIN")
     */
    public function postTeamsAction(): Response
    {
        $team = new Team();
        $postedData = $this->getPostedData();

        $form = $this
            ->createForm(
                TeamForm::class,
                $team,
                TeamForm::buildOptions(Request::METHOD_POST, $postedData)
            )
            ->submit($postedData, false);

        if (!$form->isValid()) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduceForm($form), 422);
        }

        $team = $this->get(TeamManager::class)->create($team);

        return $this->serialize($team, 'get_team', 201);
    }

    /**
     * @Put(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putTeamsAction(string $uuid, Request $request): Response
    {
        $content = json_decode($request->getContent());
        $team = $this->find(Team::class, $uuid);
        $teamData = $this->deserialize(Team::class, 'put_team');
        $region = $this->find(Region::class, $content->region->uuid);
        $teamData->setRegion($region);

        $violationList = $this->get('validator')->validate($teamData, null, ['put_team']);
        if ($violationList->count() > 0) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduce($violationList), 422);
        }

        $team = $this->get(TeamManager::class)->update($team, $teamData);

        return $this->serialize($team, 'get_team');
    }

    /**
     * @Delete(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteTeamsAction(string $uuid): Response
    {
        $team = $this->find(Team::class, $uuid);

        $this->get(TeamManager::class)->delete($team);

        return new JsonResponse(null, 204);
    }
}
