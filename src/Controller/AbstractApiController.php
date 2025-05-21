<?php

namespace App\Controller;

use App\Entity\UsuarioPanel;
use App\Repository\UsuarioPanelRepository;
use App\Service\AudiHelperService;
use App\Service\LoggerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(
        private readonly AudiHelperService        $audiHelper,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly LoggerService          $logger
    )
    {
    }


    protected function obtenerUsuarioPorAudiUser(?int $id): ?string
    {
        return $this->audiHelper->obtenerUsuarioPorAudiUser($id);
    }

    /**
     * @param $entity
     * @return void
     */
    protected function destroy($entity): void
    {
        $entity->setAudiUser($this->getUser()?->getAuditId());
        $entity->setAudiDate(new DateTime());
        $entity->setAudiAction('D');

        $this->entityManager->flush();
    }

    protected function safeGetEntity(callable $getter, string $label, array $context = []): mixed
    {
        try {
            return $getter();
        } catch (\Throwable $e) {
            $this->logger->log('data_corruption', "$label inaccesible: " . $e->getMessage(), $context);
            return null;
        }
    }

}
