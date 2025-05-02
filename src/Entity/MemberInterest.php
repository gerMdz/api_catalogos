<?php

namespace App\Entity;

use App\Repository\MemberInterestRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberInterestRepository::class)]
#[ORM\Table(name: 'members_interests')]
class MemberInterest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'members_id', referencedColumnName: 'id')]
    private ?Member $member = null;


    #[ORM\ManyToOne(targetEntity: Interest::class)]
    #[ORM\JoinColumn(name: 'interests_id', referencedColumnName: 'id')]
    private ?Interest $interest = null;


    #[ORM\Column(name: 'audi_user', type: 'integer', nullable: true)]
    private ?int $audiUser = null;

    #[ORM\Column(name: 'audi_date', type: 'datetime', nullable: true)]
    private ?DateTimeInterface $audiDate = null;

    #[ORM\Column(name: 'audi_action', type: 'string', length: 1, nullable: true)]
    private ?string $audiAction = null;

// Getters y setters estándar con $this->
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMember(): ?Member { return $this->member; }
    public function setMember(?Member $member): self {
        $this->member = $member;
        return $this;
    }

    public function getInterest(): ?Interest { return $this->interest; }
    public function setInterest(?Interest $interest): self {
        $this->interest = $interest;
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
