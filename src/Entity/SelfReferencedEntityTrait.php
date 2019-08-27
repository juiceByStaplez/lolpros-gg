<?php

namespace App\Entity;

use JMS\Serializer\SerializationContext;

trait SelfReferencedEntityTrait
{
    /**
     * @param SerializationContext $context
     *
     * @return bool
     */
    public function isChild(SerializationContext $context)
    {
        $groups = $context->attributes->get('groups')->get();

        $childDepth = in_array('pagination', $groups) ? 2 : 1;

        return $context->getDepth() > $childDepth;
    }
}
