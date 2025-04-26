<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $Nombre = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $slug = null;

    /**
     * @var Collection<int, UsuarioPanel>
     */
    #[ORM\ManyToMany(targetEntity: UsuarioPanel::class, mappedBy: 'roles')]
    private Collection $usuarios;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->id = Uuid::v7();
    }

    public function __toString(): string
    {
        return $this->Nombre;
    }

    public function getId(): UuidV7|Uuid|null
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): static
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, UsuarioPanel>
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(UsuarioPanel $usuario): static
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios->add($usuario);
            $usuario->addRole($this);
        }

        return $this;
    }

    public function removeUsuario(UsuarioPanel $usuario): static
    {
        if ($this->usuarios->removeElement($usuario)) {
            $usuario->removeRole($this);
        }

        return $this;
    }
}
