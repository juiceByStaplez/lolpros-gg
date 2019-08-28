<?php

namespace App\Entity\LeagueOfLegends\Player;

use App\Entity\SelfReferencedEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="player__league__summoner_name")
 * @ORM\Entity(repositoryClass="App\Repository\LeagueOfLegends\SummonerNameRepository")
 */
class SummonerName
{
    use SelfReferencedEntityTrait;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     * @Serializer\Type("string")
     * @Assert\NotNull
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @var RiotAccount
     * @ORM\ManyToOne(targetEntity="App\Entity\LeagueOfLegends\Player\RiotAccount", inversedBy="summonerNames")
     * @Serializer\Type("string")
     */
    protected $owner;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     * @Serializer\Type("boolean")
     */
    protected $current;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     * @Serializer\Type("DateTime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="changed_at", type="datetime")
     * @Serializer\Type("DateTime")
     */
    protected $changedAt;

    /**
     * @var SummonerName
     * @ORM\OneToOne(targetEntity="App\Entity\LeagueOfLegends\Player\SummonerName", inversedBy="next")
     * @Serializer\Type("App\Entity\LeagueOfLegends\Player\SummonerName")
     * @Serializer\Exclude(if="object.isChild(context)")
     */
    protected $previous;

    /**
     * @var SummonerName
     * @ORM\OneToOne(targetEntity="App\Entity\LeagueOfLegends\Player\SummonerName", mappedBy="previous")
     * @Serializer\Type("App\Entity\LeagueOfLegends\Player\SummonerName")
     * @Serializer\Exclude(if="object.isChild(context)")
     */
    protected $next;

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOwner(): RiotAccount
    {
        return $this->owner;
    }

    public function setOwner(RiotAccount $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function isCurrent(): bool
    {
        return $this->current;
    }

    public function setCurrent(bool $current): self
    {
        $this->current = $current;

        return $this;
    }

    public function setPrevious(self $summonerName): self
    {
        $this->previous = $summonerName;

        return $this;
    }

    public function getPrevious(): ?self
    {
        return $this->previous;
    }

    public function setNext(self $summonerName): self
    {
        $this->next = $summonerName;

        return $this;
    }

    public function getNext(): ?self
    {
        return $this->next;
    }

    /**
     * @Serializer\VirtualProperty()
     */
    public function getPlayer(): Player
    {
        return $this->owner->getPlayer();
    }
}
