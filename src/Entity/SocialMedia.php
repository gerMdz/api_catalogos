<?php

namespace App\Entity;

use App\Repository\SocialMediaRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocialMediaRepository::class)]
#[ORM\Table(name: "social_media")]
class SocialMedia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $audi_user = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTimeInterface $audi_date = null;

    #[ORM\Column(type: "string", length: 1, nullable: true)]
    private ?string $audi_action = null;


    public function __toString(): string
    {
        return (string)$this->name;
    }

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
        return $this->audi_user;
    }

    public function setAudiUser(?int $audi_user): self
    {
        $this->audi_user = $audi_user;
        return $this;
    }

    public function getAudiDate(): ?DateTimeInterface
    {
        return $this->audi_date;
    }

    public function setAudiDate(?DateTimeInterface $audi_date): self
    {
        $this->audi_date = $audi_date;
        return $this;
    }

    public function getAudiAction(): ?string
    {
        return $this->audi_action;
    }

    public function setAudiAction(?string $audi_action): self
    {
        $this->audi_action = $audi_action;
        return $this;
    }
}
