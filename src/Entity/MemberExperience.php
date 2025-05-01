<?php
// src/Entity/MemberExperience.php
namespace App\Entity;

use App\Repository\MemberExperienceRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberExperienceRepository::class)]
#[ORM\Table(name: 'members_experiences')]
class MemberExperience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'members_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Member $member = null;

    #[ORM\ManyToOne(targetEntity: Experience::class)]
    #[ORM\JoinColumn(name: 'experiences_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Experience $experience = null;

    #[ORM\Column(name: 'audi_user', type: 'integer', nullable: true)]
    private ?int $audiUser = null;

    #[ORM\Column(name: 'audi_date', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $audiDate = null;

    #[ORM\Column(name: 'audi_action', type: 'string', length: 1, nullable: true)]
    private ?string $audiAction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;
        return $this;
    }

    public function getExperience(): ?Experience
    {
        return $this->experience;
    }

    public function setExperience(?Experience $experience): self
    {
        $this->experience = $experience;
        return $this;
    }

    public function getAudiUser(): ?int
    {
        return $this->audiUser;
    }

    public function setAudiUser(?int $audiUser): self
    {
        $this->audiUser = $audiUser;
        return $this;
    }

    public function getAudiDate(): ?DateTimeInterface
    {
        return $this->audiDate;
    }

    public function setAudiDate(?DateTimeInterface $audiDate): self
    {
        $this->audiDate = $audiDate;
        return $this;
    }

    public function getAudiAction(): ?string
    {
        return $this->audiAction;
    }

    public function setAudiAction(?string $audiAction): self
    {
        $this->audiAction = $audiAction;
        return $this;
    }
}
