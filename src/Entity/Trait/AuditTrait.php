<?php

namespace App\Entity\Trait;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
trait AuditTrait
{
    #[ORM\Column(nullable: true)]
    private ?int $audiUser = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?DateTimeInterface $audiDate = null;

    #[ORM\Column(type: "string", length: 1, nullable: true)]
    private ?string $audiAction = null;

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