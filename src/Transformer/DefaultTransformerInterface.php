<?php

namespace App\Transformer;

use Elastica\Document;
use FOS\ElasticaBundle\Transformer\ModelToElasticaTransformerInterface;

interface DefaultTransformerInterface extends ModelToElasticaTransformerInterface
{
    public function fetchAndTransform($document, array $fields): ?Document;
}
