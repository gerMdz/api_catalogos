<?php

namespace App\Entity;

use App\Repository\MemberVoluntaryRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberVoluntaryRepository::class)]
#[ORM\Table(name: 'members_voluntary')]
class MemberVoluntary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'members_id', referencedColumnName: 'id', nullable: true)]
    private ?Member $member = null;

    #[ORM\ManyToOne(targetEntity: Voluntary::class)]
    #[ORM\JoinColumn(name: 'voluntary_id', referencedColumnName: 'id', nullable: true)]
    private ?Voluntary $voluntary = null;

    #[ORM\Column(type: 'boolean', nullable: true, options: ['default' => false])]
    private ?bool $service = false;

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

    public function getVoluntary(): ?Voluntary
    {
        return $this->voluntary;
    }

    public function setVoluntary(?Voluntary $voluntary): self
    {
        $this->voluntary = $voluntary;
        return $this;
    }

    public function getService(): ?bool
    {
        return $this->service;
    }

    public function setService(?bool $service): self
    {
        $this->service = $service;
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
