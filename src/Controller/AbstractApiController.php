<?php

namespace App\Controller;

use App\Entity\UsuarioPanel;
use App\Repository\UsuarioPanelRepository;
use App\Service\AudiHelperService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(private readonly AudiHelperService $audiHelper)
    {
    }


    protected function obtenerUsuarioPorAudiUser(?int $id): ?string
    {
        return $this->audiHelper->obtenerUsuarioPorAudiUser($id);
    }
}
