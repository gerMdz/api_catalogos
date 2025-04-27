<?php

namespace App\Controller;

use App\Entity\Enjoy;
use App\Repository\EnjoyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/enjoys')]
class EnjoyController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(EnjoyRepository $enjoyRepository): JsonResponse
    {
        $enjoys = $enjoyRepository->findAll();

        $data = array_map(function (Enjoy $enjoy) {
            return [
                'id' => $enjoy->getId(),
                'name' => $enjoy->getName(),
                'audiUser' => $enjoy->getAudiUser(),
                'audiDate' => $enjoy->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $enjoy->getAudiAction(),
            ];
        }, $enjoys);

        return new JsonResponse($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $enjoy = new Enjoy();
        $enjoy->setName($data['name'] ?? null);
        $enjoy->setAudiUser($data['audi_user'] ?? null);
        $enjoy->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $enjoy->setAudiAction('I');

        $em->persist($enjoy);
        $em->flush();

        return new JsonResponse(['message' => 'Disfrute creado.'], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, EnjoyRepository $enjoyRepository): JsonResponse
    {
        $enjoy = $enjoyRepository->find($id);

        if (!$enjoy) {
            return new JsonResponse(['error' => 'Disfrute no encontrado.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $enjoy->setName($data['name'] ?? $enjoy->getName());
        $enjoy->setAudiUser($data['audi_user'] ?? null);
        $enjoy->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $enjoy->setAudiAction('U');

        $em->flush();

        return new JsonResponse(['message' => 'Disfrute actualizado.']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, EnjoyRepository $enjoyRepository): JsonResponse
    {
        $enjoy = $enjoyRepository->find($id);

        if (!$enjoy) {
            return new JsonResponse(['error' => 'Disfrute no encontrado.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $enjoy->setAudiUser($data['audi_user'] ?? null);
        $enjoy->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $enjoy->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Disfrute marcado como eliminado.']);
    }
}
