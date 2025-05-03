<?php

namespace App\Service;

use App\Repository\UsuarioPanelRepository;

class AudiHelperService
{
    public function __construct(private readonly UsuarioPanelRepository $repo)
    {
    }

    /**
     * Devuelve el nombre del UsuarioPanel dado su ID.
     * Si el ID es null, retorna null.
     */
    public function obtenerUsuarioPorAudiUser(?int $id): ?string
    {
        if (!$id) {
            return null;
        }

        return $this->repo->findOneBy(['auditId' => $id])?->getNombre();
    }
}
