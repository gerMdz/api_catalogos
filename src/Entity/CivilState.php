<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'civil_state')]
class CivilState
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $audiUser = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $audiDate = null;

    #[ORM\Column(type: 'string', length: 1, nullable: true)]
    private ?string $audiAction = null;

    /**
     * @var Collection<int, Member>
     */
    #[ORM\OneToMany(targetEntity: Member::class, mappedBy: 'civilState')]
    private Collection $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    } // 'I', 'U' o 'D'

    // Getters y Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
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

    /**
     * @return Collection<int, Member>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(Member $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->setCivilState($this);
        }

        return $this;
    }

    public function removeMember(Member $member): static
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getCivilState() === $this) {
                $member->setCivilState(null);
            }
        }

        return $this;
    }
}
