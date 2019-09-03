<?php

namespace App\Entity;

use JMS\Serializer\SerializationContext;

trait SelfReferencedEntityTrait
{
    public function isChild(SerializationContext $context): bool
    {
        $groups = $context->getAttribute('groups');
        $childDepth = in_array('pagination', $groups) ? 2 : 1;

        return $context->getDepth() > $childDepth;
    }
}
