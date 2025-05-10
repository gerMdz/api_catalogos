<?php

namespace App\Entity;

use App\Entity\Trait\AuditTrait;
use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, Districts>
     */
    #[ORM\OneToMany(targetEntity: Districts::class, mappedBy: 'apiState')]
    private Collection $districts;

    public function __construct()
    {
        $this->districts = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Districts>
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    public function addDistrict(Districts $district): static
    {
        if (!$this->districts->contains($district)) {
            $this->districts->add($district);
            $district->setApiState($this);
        }

        return $this;
    }

    public function removeDistrict(Districts $district): static
    {
        if ($this->districts->removeElement($district)) {
            // set the owning side to null (unless already changed)
            if ($district->getApiState() === $this) {
                $district->setApiState(null);
            }
        }

        return $this;
    }
}
