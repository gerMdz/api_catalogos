<?php

namespace App\Entity;

use App\Repository\MemberFamilyRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: MemberFamilyRepository::class)]
#[ORM\Table(name: 'members_family')]
class MemberFamily
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'members_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Member $member = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'related_member_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Member $relatedMember = null;

    #[ORM\ManyToOne(targetEntity: Family::class)]
    #[ORM\JoinColumn(name: 'family_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private ?Family $family = null;

    #[ORM\Column(type: 'string', length: 2, nullable: true)]
    private ?string $asistChurch = null;

    #[ORM\Column(type: 'string', length: 2, nullable: true)]
    private ?string $coexists = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $audiUser = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $audiDate = null;

    #[ORM\Column(type: 'string', length: 1, nullable: true)]
    private ?string $audiAction = null;

    // Getters y Setters

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

    public function getRelatedMember(): ?Member
    {
        return $this->relatedMember;
    }

    public function setRelatedMember(?Member $relatedMember): void
    {
        $this->relatedMember = $relatedMember;
    }

    public function getFamily(): ?Family
    {
        return $this->family;
    }

    public function setFamily(?Family $family): void
    {
        $this->family = $family;
    }

    public function getAsistChurch(): ?string
    {
        return $this->asistChurch;
    }

    public function setAsistChurch(?string $asistChurch): void
    {
        $this->asistChurch = $asistChurch;
    }

    public function getCoexists(): ?string
    {
        return $this->coexists;
    }

    public function setCoexists(?string $coexists): void
    {
        $this->coexists = $coexists;
    }

    public function getAudiUser(): ?int
    {
        return $this->audiUser;
    }

    public function setAudiUser(?int $audiUser): void
    {
        $this->audiUser = $audiUser;
    }

    public function getAudiDate(): ?DateTimeInterface
    {
        return $this->audiDate;
    }

    public function setAudiDate(?DateTimeInterface $audiDate): void
    {
        $this->audiDate = $audiDate;
    }

    public function getAudiAction(): ?string
    {
        return $this->audiAction;
    }

    public function setAudiAction(?string $audiAction): void
    {
        $this->audiAction = $audiAction;
    }


}
