<?php

namespace App\Entity\Core\Team;

use App\Entity\Core\Player\Player;
use App\Entity\StringUuidTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="team__members")
 * @ORM\Entity
 */
class Member
{
    use StringUuidTrait;
    const MEMBER_STAFF = 'staff';
    const MEMBER_PLAYER = 'player';

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
     * @var Player
     * @ORM\ManyToOne(targetEntity="App\Entity\Player\Player", inversedBy="memberships")
     * @Serializer\Type("App\Entity\Player\Player")
     */
    protected $player;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="App\Entity\Team\Team", inversedBy="members")
     * @Serializer\Type("App\Entity\Team\Team")
     */
    protected $team;

    /**
     * @var DateTime
     * @ORM\Column(name="join_date", type="datetime", nullable=true)
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    protected $joinDate;

    /**
     * @var DateTime
     * @ORM\Column(name="leave_date", type="datetime", nullable=true)
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    protected $leaveDate;

    /**
     * @var string
     * @ORM\Column(name="role", type="string")
     * @Serializer\Type("string")
     * @Assert\NotNull(groups={"post_team_member"})
     * @Assert\Choice(callback="getAvailableRoles", strict=true)
     */
    protected $role;

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
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getJoinDate(): ?DateTime
    {
        return $this->joinDate;
    }

    public function setJoinDate(DateTime $joinDate): self
    {
        $this->joinDate = $joinDate;

        return $this;
    }

    public function getLeaveDate(): ?DateTime
    {
        return $this->leaveDate;
    }

    public function setLeaveDate(?DateTime $leaveDate): self
    {
        $this->leaveDate = $leaveDate;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function isCurrent(): bool
    {
        return !$this->leaveDate;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public static function getAvailableRoles(): array
    {
        return [
            self::MEMBER_STAFF,
            self::MEMBER_PLAYER,
        ];
    }
}
