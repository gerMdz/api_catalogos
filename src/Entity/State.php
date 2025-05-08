<?php

namespace App\Entity;

use App\Entity\Trait\AuditTrait;
use App\Repository\StateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StateRepository::class)]
#[ORM\Table(name: "states")]
class State
{
    use AuditTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(name: 'api_states_id', length: 11)]
    private ?string $apiState = null;

    #[ORM\ManyToOne(inversedBy: 'states')]
    #[ORM\JoinColumn(name: 'countries_id', nullable: false)]
    private ?Country $country = null;

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

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getApiState(): ?string
    {
        return $this->apiState;
    }

    public function setApiState(string $apiState): static
    {
        $this->apiState = $apiState;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }
}
