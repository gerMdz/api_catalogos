<?php

namespace App\Entity;

use App\Repository\LocalityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocalityRepository::class)]
#[ORM\Table(name: "localities")]
class Locality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(name: 'api_localities_id', length: 11)]
    private ?string $apiLocality = null;


    #[ORM\Column(name: 'api_districts_id', nullable: false)]
    private ?string $apiDistrict = null;

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

    public function getApiLocality(): ?string
    {
        return $this->apiLocality;
    }

    public function setApiLocality(string $apiLocality): static
    {
        $this->apiLocality = $apiLocality;

        return $this;
    }

    public function getApiDistrict(): ?string
    {
        return $this->apiDistrict;
    }

    public function setApiDistrict(?string $apiDistrict): static
    {
        $this->apiDistrict = $apiDistrict;

        return $this;
    }
}
