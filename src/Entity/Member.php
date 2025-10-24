<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Category;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: 'members')]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(length: 100)]
    private string $lastname;

    #[ORM\Column(type: 'date')]
    private ?DateTimeInterface $birthdate;

    #[ORM\Column(length: 20)]
    private string $dniDocument;

    #[ORM\Column(length: 255)]
    private string $address;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pathPhoto = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nameProfession = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $artisticSkills = null;

    #[ORM\Column(nullable: true)]
    private ?int $countryId = null;

    #[ORM\Column(nullable: true)]
    private ?int $stateId = null;

    #[ORM\Column(nullable: true)]
    private ?int $districtId = null;

    #[ORM\Column(nullable: true)]
    private ?int $localitiesId = null;

    #[ORM\Column(options: ["default" => false])]
    private bool $bossFamily = false;

    #[ORM\Column(nullable: true)]
    private ?int $quantitySons = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $celebracion = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nameGuia = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nameGroup = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $grupo = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $participateGp = null;

    #[ORM\Column(nullable: true)]
    private ?int $audiUser = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $audiDate = null;

    #[ORM\Column(type: 'string', length: 1, nullable: true)]
    private ?string $audiAction = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\Column(name: 'gender_id', type: 'integer')]
    private ?int $gender = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\Column(name: 'civil_state_id')]
    private ?int $civilState = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Category $category = null;

    public function __toString(): string
    {
        return trim("{$this->lastname}, {$this->name}");
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function getDniDocument(): string
    {
        return $this->dniDocument;
    }

    public function setDniDocument(string $dniDocument): void
    {
        $this->dniDocument = $dniDocument;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }


    public function getPathPhoto(): ?string
    {
        return $this->pathPhoto;
    }

    public function setPathPhoto(?string $pathPhoto): void
    {
        $this->pathPhoto = $pathPhoto;
    }

    public function getNameProfession(): ?string
    {
        return $this->nameProfession;
    }

    public function setNameProfession(?string $nameProfession): void
    {
        $this->nameProfession = $nameProfession;
    }

    public function getArtisticSkills(): ?string
    {
        return $this->artisticSkills;
    }

    public function setArtisticSkills(?string $artisticSkills): void
    {
        $this->artisticSkills = $artisticSkills;
    }

    public function getCountryId(): ?int
    {
        return $this->countryId;
    }

    public function setCountryId(?int $countryId): void
    {
        $this->countryId = $countryId;
    }

    public function getStateId(): ?int
    {
        return $this->stateId;
    }

    public function setStateId(?int $stateId): void
    {
        $this->stateId = $stateId;
    }

    public function getDistrictId(): ?int
    {
        return $this->districtId;
    }

    public function setDistrictId(?int $districtId): void
    {
        $this->districtId = $districtId;
    }

    public function getLocalitiesId(): ?int
    {
        return $this->localitiesId;
    }

    public function setLocalitiesId(?int $localitiesId): void
    {
        $this->localitiesId = $localitiesId;
    }

    public function isBossFamily(): bool
    {
        return $this->bossFamily;
    }

    public function setBossFamily(bool $bossFamily): void
    {
        $this->bossFamily = $bossFamily;
    }

    public function getQuantitySons(): ?int
    {
        return $this->quantitySons;
    }

    public function setQuantitySons(?int $quantitySons): void
    {
        $this->quantitySons = $quantitySons;
    }

    public function getCelebracion(): ?string
    {
        return $this->celebracion;
    }

    public function setCelebracion(?string $celebracion): void
    {
        $this->celebracion = $celebracion;
    }

    public function getNameGuia(): ?string
    {
        return $this->nameGuia;
    }

    public function setNameGuia(?string $nameGuia): void
    {
        $this->nameGuia = $nameGuia;
    }

    public function getNameGroup(): ?string
    {
        return $this->nameGroup;
    }

    public function setNameGroup(?string $nameGroup): void
    {
        $this->nameGroup = $nameGroup;
    }

    public function getGrupo(): ?string
    {
        return $this->grupo;
    }

    public function setGrupo(?string $grupo): void
    {
        $this->grupo = $grupo;
    }

    public function getParticipateGp(): ?string
    {
        return $this->participateGp;
    }

    public function setParticipateGp(?string $participateGp): void
    {
        $this->participateGp = $participateGp;
    }

    public function getAudiUser(): ?int
    {
        return $this->audiUser;
    }

    public function setAudiUser(?int $audiUser): void
    {
        $this->audiUser = $audiUser;
    }

    public function getAudiDate(): ?DateTimeInterface
    {
        return $this->audiDate;
    }

    public function setAudiDate(?DateTimeInterface $audiDate): void
    {
        $this->audiDate = $audiDate;
    }

    public function getAudiAction(): ?string
    {
        return $this->audiAction;
    }

    public function setAudiAction(?string $audiAction): void
    {
        $this->audiAction = $audiAction;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender($gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getCivilState(): ?int
    {
        return $this->civilState;
    }

    public function setCivilState($civilState): static
    {
        $this->civilState = $civilState;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getNombreCompleto(): string
    {
        return trim($this->getName() . ' ' . $this->getLastname());
    }

    public function getNombreCompletoConDni(): string
    {
        return $this->getNombreCompleto() . ' (' . $this->getDniDocument() . ')';
    }



}
