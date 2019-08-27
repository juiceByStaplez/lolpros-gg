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
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Serializer\Exclude()
     */
    protected $id;

    /**
     * @var UuidInterface
     *
     * @ORM\Column(name="uuid", type="uuid", nullable=false)
     *
     * @Serializer\Type("string")
     * @Serializer\Groups({
     *     "league.get_players",
     *     "league.get_player",
     *     "league.get_players_ranking",
     *	   "league.get_riot_account",
     *     "league.post_riot_account_initialize",
     *     "league.get_summoner_name",
     *     "league.get_search",
     *     "get_player_social_medias",
     *     "get_teams",
     *     "get_team_members",
     *     "get_member",
     *     "get_team",
     *     "get_reports",
     *     "get_report",
     * })
     */
    protected $uuid;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @Serializer\Type("string")
     * @Serializer\Groups({
     *     "league.get_players",
     *     "league.get_player",
     *     "league.post_player",
     *     "league.put_player",
     *     "league.get_riot_account",
     *     "league.get_riot_account",
     *     "league.get_summoner_name",
     *     "league.get_search",
     *     "get_teams",
     *     "get_team_members",
     *     "get_member",
     *     "get_team",
     *     "get_reports",
     *     "get_report",
     * })
     *
     * @Assert\NotNull(groups={
     *     "league.post_player",
     * })
     */
    protected $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", nullable=false)
     *
     * @Serializer\Type("string")
     * @Serializer\Groups({
     *     "league.get_players",
     *     "league.get_player",
     *     "league.post_player",
     *     "league.get_riot_account",
     *     "league.get_summoner_name",
     *     "league.get_search",
     *     "get_team_members",
     *     "get_team",
     *     "get_teams",
     *     "get_reports",
     *     "get_report",
     * })
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Serializer\Type("string")
     * @Serializer\Groups({
     *     "league.get_players",
     *     "league.get_player",
     *     "league.post_player",
     *     "league.put_player",
     *     "get_team_members",
     *     "league.get_search",
     *     "get_team",
     *     "get_teams",
     *     "get_reports",
     * })
     *
     * @Assert\NotNull(groups={
     *     "league.post_player",
     * })
     */
    protected $country;

    /**
     * @var ArrayCollection|Member[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Team\Member", mappedBy="player")
     * @ORM\OrderBy({"joinDate"="DESC"})
     *
     * @Serializer\Type("App\Entity\Team\Member")
     */
    protected $memberships;

    /**
     * @var SocialMedia
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Player\SocialMedia", mappedBy="owner", cascade={"persist", "remove"})
     *
     * @Serializer\Type("App\Entity\Player\SocialMedia")
     * @Serializer\Groups({
     *     "get_player_social_medias",
     *     "league.get_player",
     * })
     */
    protected $socialMedia;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Type("DateTime")
     * @Serializer\Groups({
     *     "league.get_player",
     * })
     */
    protected $createdAt;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Type("DateTime")
     * @Serializer\Groups({
     *     "league.get_player",
     * })
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
     * @Serializer\Groups({
     *     "league.get_player",
     * })
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
