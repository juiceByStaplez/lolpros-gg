<?php

namespace App\Controller;

use Doctrine\Common\Inflector\Inflector;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use JMS\Serializer\DeserializationContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class APIController extends AbstractFOSRestController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    protected function serialize($data, $groups = null, $code = 200): Response
    {
        $context = (new Context())->setSerializeNull(true);

        return $this->handleView($this->view($data, $code)->setContext($context)->setFormat('json'));
    }

    protected function deserialize(string $class, string $group = null)
    {
        $content = $this->get('request_stack')->getCurrentRequest()->getContent();

        if (!$content) {
            return $content;
        }

        try {
            return $this->get('jms_serializer')->deserialize($content, $class, 'json', DeserializationContext::create()->setGroups([$group]));
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    protected function getPostedData($data = null): array
    {
        $rawData = (array) $data;

        if (!$data) {
            $content = $this->get('request_stack')->getCurrentRequest()->getContent();

            $rawData = json_decode($content, true);

            if (!is_array($rawData)) {
                throw new BadRequestHttpException();
            }
        }

        $correctData = [];

        unset($rawData['uuid']);
        foreach ($rawData as $field => $value) {
            $correctData[Inflector::camelize($field)] = $value;
        }

        return $correctData;
    }

    protected function find(string $namespace, string $uuid)
    {
        $entity = $this->getDoctrine()->getRepository($namespace)->findOneBy([
            'uuid' => $uuid,
        ]);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    protected function findOne(array $namespaces, string $uuid)
    {
        foreach ($namespaces as $namespace) {
            $entity = $this->getDoctrine()->getRepository($namespace)->findOneBy([
                'uuid' => $uuid,
            ]);
            if ($entity) {
                break;
            }
        }

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    protected function findBy(string $namespace, array $params)
    {
        $entity = $this->getDoctrine()->getRepository($namespace)->findBy($params);

        if (!$entity) {
            throw new NotFoundHttpException();
        }

        return $entity;
    }

    protected function getNullOrBoolean($param): ?bool
    {
        if (null !== $param) {
            return filter_var($param, FILTER_VALIDATE_BOOLEAN);
        }

        return null;
    }
}
