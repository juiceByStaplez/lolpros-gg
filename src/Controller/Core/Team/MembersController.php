<?php

namespace App\Controller\Core\Team;

use App\Controller\APIController;
use App\Entity\Core\Team\Member;
use App\Form\Core\Team\MemberForm;
use App\Manager\Core\Team\MemberManager;
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
 * @Route("/members")
 */
class MembersController extends APIController
{
    /**
     * @Get(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getMemberAction(string $uuid): Response
    {
        $member = $this->find(Member::class, $uuid);

        return $this->serialize($member, 'get_member');
    }

    /**
     * @Post(path="")
     * @IsGranted("ROLE_ADMIN")
     */
    public function postMembersAction(): Response
    {
        $member = new Member();
        $postedData = $this->getPostedData();

        $form = $this
            ->createForm(MemberForm::class, $member, MemberForm::buildOptions(Request::METHOD_POST, $postedData))
            ->submit($postedData, false);

        if (!$form->isValid()) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduceForm($form), 422);
        }

        $member = $this->get(MemberManager::class)->create($member);

        return $this->serialize($member, 'get_member', 201);
    }

    /**
     * @Put(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putMembersAction(string $uuid): Response
    {
        $member = $this->find(Member::class, $uuid);
        $postedData = $this->getPostedData();

        $form = $this
            ->createForm(MemberForm::class, $member, MemberForm::buildOptions(Request::METHOD_PUT, $postedData))
            ->submit($postedData, false);

        if (!$form->isValid()) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduceForm($form), 422);
        }

        $member = $this->get(MemberManager::class)->update($member);

        return $this->serialize($member, 'get_member');
    }

    /**
     * @Delete(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteMembersAction(string $uuid): Response
    {
        $member = $this->find(Member::class, $uuid);

        $this->get(MemberManager::class)->delete($member);

        return new JsonResponse(null, 204);
    }
}
