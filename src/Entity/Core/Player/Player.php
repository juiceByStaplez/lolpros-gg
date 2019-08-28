<?php

namespace App\Entity\Core\Player;

use App\Entity\Core\Team\Member;
use App\Entity\Core\Team\Team;
use App\Entity\StringUuidTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="player__player")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=75)
 * @ORM\DiscriminatorMap({
 *     "league_player" = "App\Entity\LeagueOfLegends\Player\Player"
 * })
 * @ORM\Entity
 */
abstract class Player
{
    use StringUuidTrait;

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Exclude
     */
    protected $id;

    /**
     * @var UuidInterface
     * @ORM\Column(name="uuid", type="uuid", nullable=false)
     * @Serializer\Type("string")
     */
    protected $uuid;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"league.post_player"})
     */
    protected $name;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Type("string")
     */
    protected $slug;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"league.post_player"})
     */
    protected $country;

    /**
     * @var ArrayCollection|Member[]
     * @ORM\OneToMany(targetEntity="App\Entity\Core\Team\Member", mappedBy="player")
     * @ORM\OrderBy({"joinDate"="DESC"})
     * @Serializer\Type("App\Entity\Core\Team\Member")
     */
    protected $memberships;

    /**
     * @var SocialMedia
     * @ORM\OneToOne(targetEntity="App\Entity\Core\Player\SocialMedia", mappedBy="owner", cascade={"persist", "remove"})
     * @Serializer\Type("App\Entity\Core\Player\SocialMedia")
     */
    protected $socialMedia;

    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     * @Serializer\Type("DateTime")
     */
    protected $createdAt;

    /**
     * @var DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     * @Serializer\Type("DateTime")
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->socialMedia = new SocialMedia($this);
        $this->memberships = new ArrayCollection();
    }

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry($country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function getSocialMedia(): SocialMedia
    {
        return $this->socialMedia;
    }

    public function setSocialMedia(SocialMedia $socialMedia): self
    {
        $this->socialMedia = $socialMedia;

        return $this;
    }

    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMemberships(Member $member): self
    {
        $this->memberships->add($member);

        return $this;
    }

    public function removeMemberships(Member $member): self
    {
        $this->memberships->removeElement($member);

        return $this;
    }

    public function getCurrentMembership(): ?Member
    {
        /** @var Member $membership */
        $membership = $this->memberships->filter(function (Member $membership) {
            return $membership->isCurrent();
        })->first();

        return $membership;
    }

    /**
     * @Serializer\VirtualProperty()
     */
    public function getCurrentTeam(): ?Team
    {
        /** @var Member $membership */
        $membership = $this->memberships->filter(function (Member $membership) {
            return $membership->isCurrent();
        })->first();

        return $membership ? $membership->getTeam() : null;
    }
}
