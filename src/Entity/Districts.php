<?php

namespace App\Entity;

use App\Entity\Trait\AuditTrait;
use App\Repository\DistrictsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DistrictsRepository::class)]
#[ORM\Table(name: "districts")]
class Districts
{
    use AuditTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(name: 'api_districts_id', length: 11)]
    private ?string $apiDistrict = null;


    #[ORM\Column(name: 'api_states_id', nullable: false)]
    private ?string $apiState = null;

    /**
     * @var Collection<int, Locality>
     */
    #[ORM\OneToMany(targetEntity: Locality::class, mappedBy: 'apiDistrict')]
    private Collection $localities;

    public function __construct()
    {
        $this->localities = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
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

    public function getApiDistrict(): ?string
    {
        return $this->apiDistrict;
    }

    public function setApiDistrict(string $apiDistrict): static
    {
        $this->apiDistrict = $apiDistrict;

        return $this;
    }

    public function getApiState(): ?string
    {
        return $this->apiState;
    }

    public function setApiState(?string $apiState): static
    {
        $this->apiState = $apiState;

        return $this;
    }

    /**
     * @return Collection<int, Locality>
     */
    public function getLocalities(): Collection
    {
        return $this->localities;
    }

    public function addLocality(Locality $locality): static
    {
        if (!$this->localities->contains($locality)) {
            $this->localities->add($locality);
            $locality->setApiDistrict($this);
        }

        return $this;
    }

    public function removeLocality(Locality $locality): static
    {
        if ($this->localities->removeElement($locality)) {
            // set the owning side to null (unless already changed)
            if ($locality->getApiDistrict() === $this) {
                $locality->setApiDistrict(null);
            }
        }

        return $this;
    }


}
