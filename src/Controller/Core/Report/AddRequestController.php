<?php

namespace App\Controller\Core\Report;

use App\Controller\APIController;
use App\Entity\Core\Report\AddRequest;
use App\Form\Core\Report\AddRequestForm;
use App\Manager\Core\Report\AddRequestManager;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/add-requests")
 */
class AddRequestController extends APIController
{
    /**
     * @Get(path="")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getAddRequestsAction()
    {
        $requests = $this->getDoctrine()->getRepository(AddRequest::class)->findAll();

        return $this->serialize($requests, 'get_add_requests');
    }

    /**
     * @Get(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getAddRequestAction($uuid)
    {
        $request = $this->find(AddRequest::class, $uuid);

        return $this->serialize($request, 'get_add_request');
    }

    /**
     * @Post(path="")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function postAddRequestsAction()
    {
        $request = new AddRequest();
        $postedData = $this->getPostedData();

        $form = $this
            ->createForm(
                AddRequestForm::class,
                $request,
                AddRequestForm::buildOptions(Request::METHOD_POST)
            )
            ->submit($postedData, false);

        if (!$form->isValid()) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduceForm($form), 422);
        }

        $request = $this->get(AddRequestManager::class)->create($request);

        return $this->serialize($request, 'get_add_request', 201);
    }

    /**
     * @Put(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function putAddRequestAction($uuid)
    {
        $request = $this->find(AddRequest::class, $uuid);
        $postedData = $this->getPostedData();

        $form = $this
            ->createForm(
                AddRequestForm::class,
                $request,
                AddRequestForm::buildOptions(Request::METHOD_PUT)
            )
            ->submit($postedData, false);

        if (!$form->isValid()) {
            return new JsonResponse($this->get('service.generic.error_formatter')->reduceForm($form), 422);
        }

        $request = $this->get(AddRequestManager::class)->update($request);

        return $this->serialize($request, 'get_add_request');
    }

    /**
     * @Delete(path="/{uuid}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteAddRequestsAction($uuid)
    {
        $request = $this->find(AddRequest::class, $uuid);

        $this->get(AddRequestManager::class)->delete($request);

        return new JsonResponse(null, 204);
    }
}
