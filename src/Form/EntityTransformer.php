<?php

namespace App\Form;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;

class EntityTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * @var string
     */
    private $className;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
        $this->className = $this->repository->getClassName();
    }

    public function transform($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return bool|object|object[]|null
     */
    public function reverseTransform($value)
    {
        if (!is_array($value)) {
            return null;
        }

        if (isset($value['uuid'])) {
            return $this->fetchOne($value);
        }

        return null;
    }

    private function fetchOne($rawData): ?object
    {
        return $this->createIfDoesntExist($this->repository->findOneBy(['uuid' => $rawData['uuid']]));
    }

    private function createIfDoesntExist($entity)
    {
        if (!$entity instanceof $this->className) {
            $class = new \ReflectionClass($this->className);

            if ($class->isAbstract()) {
                return null;
            }

            return $class->newInstance();
        }

        return $entity;
    }
}
