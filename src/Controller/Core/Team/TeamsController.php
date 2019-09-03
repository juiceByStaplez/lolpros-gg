<?php

namespace App\Controller\Core\Team;

use App\Controller\APIController;
use App\Entity\Core\Team\Team;
use App\Entity\LeagueOfLegends\Region\Region;
use App\Form\Core\Team\TeamForm;
use App\Manager\Core\Team\TeamManager;
use App\Repository\Core\TeamRepository;
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
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function getTeamsAction(ParamFetcher $paramFetcher, TeamRepository $teamRepository): Response
    {
        $options = [];

        if (($active = $this->getNullOrBoolean($paramFetcher->get('active')))) {
            $options['active'] = $active;
        }

        $teams = $teamRepository->findBy($options, ['name' => 'asc']);

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
    public function postTeamsAction(TeamManager $teamManager): Response
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
            return new JsonResponse($this->errorFormatter->reduceForm($form), 422);
        }

        $team = $teamManager->create($team);

        return $this->serialize($team, 'get_team', 201);
    }

    /**
     * @Put(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putTeamsAction(string $uuid, Request $request, TeamManager $teamManager, ValidatorInterface $validator): Response
    {
        $content = json_decode($request->getContent());
        $team = $this->find(Team::class, $uuid);
        $teamData = $this->deserialize(Team::class, 'put_team');
        $region = $this->find(Region::class, $content->region->uuid);
        $teamData->setRegion($region);

        $violationList = $validator->validate($teamData, null, ['put_team']);
        if ($violationList->count() > 0) {
            return new JsonResponse($this->errorFormatter->reduce($violationList), 422);
        }

        $team = $teamManager->update($team, $teamData);

        return $this->serialize($team, 'get_team');
    }

    /**
     * @Delete(path="/{uuid}")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function deleteTeamsAction(string $uuid, TeamManager $teamManager): Response
    {
        $team = $this->find(Team::class, $uuid);

        $teamManager->delete($team);

        return new JsonResponse(null, 204);
    }
}
