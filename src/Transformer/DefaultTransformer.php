<?php

namespace App\Transformer;

use Doctrine\ORM\EntityManagerInterface;

abstract class DefaultTransformer implements DefaultTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
