<?php

namespace App\Entity;

use App\Repository\MemberServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MemberServiceRepository::class)]
#[ORM\Table(name: 'members_services')]
class MemberService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'members_id', referencedColumnName: 'id', nullable: true)]
    private ?Member $member = null;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(name: 'services_id', referencedColumnName: 'id', nullable: true)]
    private ?Service $service = null;

    #[ORM\Column(name: 'audi_user', type: 'integer', nullable: true)]
    private ?int $audiUser = null;

    #[ORM\Column(name: 'audi_date', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $audiDate = null;

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

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
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

    public function getAudiDate(): ?\DateTimeInterface
    {
        return $this->audiDate;
    }

    public function setAudiDate(?\DateTimeInterface $audiDate): self
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
