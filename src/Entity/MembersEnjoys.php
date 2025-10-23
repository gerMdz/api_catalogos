<?php

namespace App\Entity;

use App\Entity\Trait\AuditTrait;
use App\Repository\MembersEnjoysRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MembersEnjoysRepository::class)]
#[ORM\Table(name: 'members_enjoys')]
class MembersEnjoys
{
    use AuditTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'members_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Member $member = null;

    #[ORM\ManyToOne(targetEntity: Enjoy::class)]
    #[ORM\JoinColumn(name: 'enjoys_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Enjoy $enjoy = null;

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

    public function getEnjoy(): ?Enjoy
    {
        return $this->enjoy;
    }

    public function setEnjoy(?Enjoy $enjoy): self
    {
        $this->enjoy = $enjoy;
        return $this;
    }
}
