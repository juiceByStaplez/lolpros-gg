<?php

namespace App\Entity\Core\Team;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class SocialMedia.
 *
 * @ORM\Table(name="team__social_media")
 * @ORM\Entity
 */
class SocialMedia
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Team
     * @ORM\OneToOne(targetEntity="App\Entity\Team\Team", inversedBy="socialMedia")
     * @Serializer\Type("App\Entity\Team\Team")
     */
    protected $owner;

    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     * @Serializer\Type("DateTime")
     */
    protected $updatedAt;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Type("string")
     */
    protected $twitter;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Type("string")
     */
    protected $facebook;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Type("string")
     */
    protected $website;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Type("string")
     */
    protected $leaguepedia;

    public function __construct(Team $team)
    {
        $this->owner = $team;
    }

    public function getOwner(): Team
    {
        return $this->owner;
    }

    public function setOwner(Team $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getLeaguepedia(): ?string
    {
        return $this->leaguepedia;
    }

    public function setLeaguepedia(?string $leaguepedia): self
    {
        $this->leaguepedia = $leaguepedia;

        return $this;
    }
}
