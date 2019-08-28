<?php

namespace App\Model\Core;

use DateTime;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\UuidInterface;

class PlayerTeam
{
    /**
     * @var UuidInterface
     * @Serializer\Type("string")
     */
    public $uuid;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $name;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $displayName;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $slug;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $tag;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    public $logo;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     */
    public $active;

    /**
     * @var DateTime
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $joinDate;

    /**
     * @var DateTime
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public $leaveDate;

    /**
     * @var string
     * @Serializer\Type("string")
     */
    public $role;

    /**
     * @var bool
     * @Serializer\Type("boolean")
     */
    public $current;

    /**
     * @var array
     * @Serializer\Type("array")
     */
    public $members;

    /**
     * @var DateTime
     * @Serializer\Type("DateTime")
     */
    public $creationDate;

    /**
     * @var DateTime
     * @Serializer\Type("DateTime")
     */
    public $disbandDate;
}
