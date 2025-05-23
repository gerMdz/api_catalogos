<?php

// src/Controller/Api/ServiceController.php
namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use App\Repository\UsuarioPanelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/services', name: 'api_services_')]
class ServiceController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(ServiceRepository $serviceRepository): JsonResponse
    {
        $services = $serviceRepository->findBy([], ['name' => 'ASC']);
        return $this->json($services);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $service = new Service();
        $service->setName($data['name'] ?? null);
        $service->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $service->setAudiDate(new \DateTime());
        $service->setAudiAction('I');

        $em->persist($service);
        $em->flush();

        return $this->json($service);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, Service $service, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $service->setName($data['name'] ?? $service->getName());
        $service->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $service->setAudiDate(new \DateTime());
        $service->setAudiAction('U');

        $em->flush();

        return $this->json($service);
    }

    /**
     * @throws \Exception
     */
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em,
                           ServiceRepository $serviceRepository): JsonResponse
    {
        $service = $serviceRepository->find($id);

        if (!$service) {
            return new JsonResponse(['error' => 'Necesidad no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $service->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $service->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $service->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Necesidad marcada como eliminada.']);
    }


    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Service $service, UsuarioPanelRepository $usuarioPanelRepository): JsonResponse
    {
        static $usuariosCache = [];

        $auditUser = null;
        $idAudit = $service->getAudiUser();

        if ($idAudit) {
            if (!isset($usuariosCache[$idAudit])) {
                $userAction = $usuarioPanelRepository->findOneBy(['auditId' => $idAudit]);
                $usuariosCache[$idAudit] = $userAction;
            } else {
                $userAction = $usuariosCache[$idAudit];
            }

            if ($userAction) {
                $auditUser = [
                    'nombre' => $userAction->getNombre(),
                    'email' => $userAction->getEmail()
                ];
            }
        }

        return $this->json([
            'id' => $service->getId(),
            'name' => $service->getName(),
            'audi_user' => $auditUser,
            'audi_date' => $service->getAudiDate()?->format('Y-m-d H:i:s'),
            'audi_action' => $service->getAudiAction(),
        ]);
    }


}
