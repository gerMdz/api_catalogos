<?php

// src/Controller/Api/ServiceController.php
namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        $service->setAudiUser($this->getUser()?->getId() ?? null);
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
        $service->setAudiUser($this->getUser()?->getId() ?? null);
        $service->setAudiDate(new \DateTime());
        $service->setAudiAction('U');

        $em->flush();

        return $this->json($service);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Service $service, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($service);
        $em->flush();

        return $this->json(null, 204);
    }
}
