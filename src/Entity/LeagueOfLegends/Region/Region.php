<?php

namespace App\Entity\LeagueOfLegends\Region;

use App\Entity\Core\Team\Team;
use App\Entity\LeagueOfLegends\Document\RegionLogo;
use App\Entity\LeagueOfLegends\Player\Player;
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
 * Class Region.
 *
 * @ORM\Table(name="region__region")
 * @ORM\Entity
 */
class Region
{
    use StringUuidTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var UuidInterface
     *
     * @ORM\Column(type="uuid", nullable=false)
     *
     * @Serializer\Type("string")
     * @Serializer\Groups({
     *     "league.get_region",
     *     "league.get_regions",
     *     "league.get_player",
     *     "league.get_players",
     *     "league.get_search",
     *     "get_document",
     *     "get_teams",
     *     "get_team",
     *     "post_team",
     *     "put_team",
     *     "get_player_members",
     * })
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    protected $uuid;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @Serializer\Type("string")
     * @Serializer\Groups({
     *     "league.get_region",
     *     "league.get_regions",
     *     "league.post_region",
     *     "league.put_region",
     *     "league.get_player",
     *     "league.get_players",
     *     "league.get_search",
     *     "get_teams",
     *     "get_team",
     *     "get_player_members",
     * })
     *
     * @Assert\NotNull(groups={
     *     "league.post_region",
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
     *     "league.get_region",
     *     "league.get_regions",
     *     "league.get_player",
     *     "league.get_players",
     *     "league.get_search",
     *     "get_teams",
     *     "get_team",
     * })
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @Serializer\Type("string")
     * @Serializer\Groups({
     *     "league.get_region",
     *     "league.get_regions",
     *     "league.put_region",
     *     "league.get_player",
     *     "league.get_players",
     *     "league.get_search",
     *     "get_teams",
     *     "get_team",
     * })
     *
     * @Assert\NotNull(groups={
     *     "league.post_region",
     * })
     */
    protected $shorthand;

    /**
     * @var array
     *
     * @ORM\Column(type="array", nullable=true)
     *
     * @Serializer\Type("array")
     * @Serializer\Groups({
     *     "league.get_region",
     *     "league.get_regions",
     *     "league.post_region",
     *     "league.put_region",
     * })
     *
     * @Assert\NotBlank(groups={
     *     "league.post_region",
     * })
     */
    protected $countries;

    /**
     * @var ArrayCollection|Player[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\LeagueOfLegends\Player\Player", mappedBy="regions")
     *
     * @Serializer\Type("array<LeagueOfLegends\Entity\Player\Player>")
     */
    protected $players;

    /**
     * @var ArrayCollection|Team[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Team\Team", mappedBy="region")
     *
     * @Serializer\Type("array<App\Entity\Team\Team>")
     */
    protected $teams;

    /**
     * @var RegionLogo
     *
     * @ORM\OneToOne(targetEntity="\LeagueOfLegends\Entity\Document\RegionLogo", mappedBy="region", cascade={"remove"})
     *
     * @Serializer\Type("App\Entity\LeagueOfLegends\Document\RegionLogo")
     * @Serializer\Groups({
     *     "league.get_region",
     *     "league.get_regions",
     *     "league.get_player",
     *     "league.get_players",
     *     "league.get_search",
     *     "get_teams",
     *     "get_team",
     * })
     */
    protected $logo;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Type("DateTime")
     * @Serializer\Groups({
     *     "league.get_region",
     *     "league.get_regions",
     * })
     */
    protected $createdAt;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Type("DateTime")
     * @Serializer\Groups({
     *     "league.get_region",
     *     "league.get_regions",
     * })
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->teams = new ArrayCollection();
        $this->players = new ArrayCollection();
        $this->countries = [];
    }

    public function getUuid(): UuidInterface
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getShorthand(): ?string
    {
        return $this->shorthand;
    }

    public function setShorthand(string $shorthand): self
    {
        $this->shorthand = $shorthand;

        return $this;
    }

    public function getCountries(): ?array
    {
        return $this->countries;
    }

    public function setCountries(array $countries): self
    {
        $this->countries = $countries;

        return $this;
    }

    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function setPlayers($players): self
    {
        $this->players = $players;

        return $this;
    }

    public function addPlayer(Player $player): self
    {
        $this->players->add($player);

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        $this->players->remove($player);

        return $this;
    }

    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function setTeams($teams): self
    {
        $this->teams = $teams;

        return $this;
    }

    public function addTeam(Team $team): self
    {
        $this->teams->add($team);

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        $this->teams->remove($team);

        return $this;
    }

    public function getLogo(): ?RegionLogo
    {
        return $this->logo;
    }

    public function setLogo(RegionLogo $logo): self
    {
        $this->logo = $logo;

        return $this;
    }
}
