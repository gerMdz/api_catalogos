<?php

namespace App\Entity;

use App\Repository\UsuarioPanelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity(repositoryClass: UsuarioPanelRepository::class)]
class UsuarioPanel implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;


    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'usuarios')]
    private Collection $roles;

    #[ORM\Column(length: 510, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $auditId = null;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->id = Uuid::v7();
    }


    public function getId(): Uuid|UuidV7|null
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }



    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }


    public function getRoles(): array
    {
        $slugs = [];

        foreach ($this->roles as $role) {
            $slugs[] = $role->getSlug();
        }

        $slugs[] = 'ROLE_USER'; // siempre aseguramos que tenga al menos ROLE_USER
        return array_unique($slugs);
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRoleEntities(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): static
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        $this->roles->removeElement($role);
        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getAuditId(): ?int
    {
        return $this->auditId;
    }

    public function setAuditId(?int $auditId): self
    {
        $this->auditId = $auditId;
        return $this;
    }

}
