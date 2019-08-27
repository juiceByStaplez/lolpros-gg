<?php

namespace App\Entity\Core\User;

use App\Entity\Core\Report\AdminLog;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FOSUser;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends FOSUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Serializer\Type("string")
     * @Serializer\Groups({
     *     "get_admin_log",
     *     "get_admin_logs",
     * })
     */
    protected $username;

    /**
     * @var Collection|AdminLog
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Report\AdminLog", mappedBy="user")
     */
    protected $edits;
}
