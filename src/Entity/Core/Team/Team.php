<?php

namespace App\Entity\Core\Team;

use App\Entity\Core\Document\TeamLogo;
use App\Entity\LeagueOfLegends\Player\Player;
use App\Entity\LeagueOfLegends\Region\Region;
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
 * Class Team.
 *
 * @ORM\Table(name="team__team")
 * @ORM\Entity(repositoryClass="App\Repository\Core\TeamRepository")
 */
class Team
{
    use StringUuidTrait;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var UuidInterface
     * @ORM\Column(type="uuid", nullable=false)
     * @Serializer\Type("string")
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    protected $uuid;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Type("string")
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Type("string")
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    protected $slug;

    /**
     * @var TeamLogo
     * @ORM\OneToOne(targetEntity="\App\Entity\Document\TeamLogo", mappedBy="team", cascade={"remove"})
     * @Serializer\Type("App\Entity\Document\TeamLogo")
     */
    protected $logo;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Type("string")
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    protected $tag;

    /**
     * @var DateTime
     * @ORM\Column(name="creation_date", type="datetime", nullable=true)
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    protected $creationDate;

    /**
     * @var DateTime
     * @ORM\Column(name="disband_date", type="datetime", nullable=true)
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    protected $disbandDate;

    /**
     * @var SocialMedia
     * @ORM\OneToOne(targetEntity="App\Entity\Team\SocialMedia", mappedBy="owner", cascade={"persist", "remove"})
     * @Serializer\Type("App\Entity\Team\SocialMedia")
     */
    protected $socialMedia;

    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="App\Entity\LeagueOfLegends\Region\Region", inversedBy="teams")
     * @Serializer\Type("App\Entity\LeagueOfLegends\Region\Region")
     * @Assert\NotNull(groups={"post_team"})
     * @Assert\NotBlank(groups={"post_team"})
     */
    protected $region;

    /**
     * @var ArrayCollection|Member[]
     * @ORM\OneToMany(targetEntity="App\Entity\Team\Member", mappedBy="team")
     * @Serializer\Type("array<App\Entity\Team\Member>")
     * @ORM\OrderBy({"leaveDate"="ASC", "joinDate"="DESC"})
     */
    protected $members;

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
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->socialMedia = new SocialMedia($this);
        $this->members = new ArrayCollection();
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function getCreationDate(): ?DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(?DateTime $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getDisbandDate(): ?DateTime
    {
        return $this->disbandDate;
    }

    public function setDisbandDate(?DateTime $disbandDate): self
    {
        $this->disbandDate = $disbandDate;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getLogo(): ?TeamLogo
    {
        return $this->logo;
    }

    public function setLogo(?TeamLogo $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
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

    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): self
    {
        $this->members->add($member);

        return $this;
    }

    public function removeMember(Member $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCurrentMemberships(): ArrayCollection
    {
        return $this->members->filter(function (Member $membership) {
            return $membership->isCurrent();
        });
    }

    public function getPreviousMemberships(): ArrayCollection
    {
        return $this->members->filter(function (Member $membership) {
            return !$membership->isCurrent();
        });
    }

    private function isAfter(DateTime $first, DateTime $second)
    {
        if ($first->format('o') < $second->format('o')) {
            return false;
        }

        if ($first->format('o') === $second->format('o') && $first->format('n') < $second->format('n')) {
            return false;
        }

        if ($first->format('o') === $second->format('o') &&
            $first->format('n') === $second->format('n') &&
            $first->format('j') < $second->format('j')) {
            return false;
        }

        return true;
    }

    public function getMembersBetweenDates(DateTime $begin, DateTime $end, $position): ?ArrayCollection
    {
        return $this->members->filter(function (Member $membership) use ($begin, $end, $position) {
            if ($this->isAfter($membership->getJoinDate(), $end)) {
                return false;
            }

            if ($membership->getLeaveDate() && !$this->isAfter($membership->getLeaveDate(), $begin)) {
                return false;
            }

            if ($membership->getPlayer() instanceof Player && $membership->getPlayer()->getPosition() === $position) {
                if ($membership->getJoinDate() == $end || $membership->getLeaveDate() == $begin) {
                    return false;
                }
            }

            return true;
        });
    }
}
