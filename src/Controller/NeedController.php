<?php

namespace App\Controller;

use App\Entity\Need;
use App\Repository\NeedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/needs')]
class NeedController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(NeedRepository $needRepository): JsonResponse
    {
        $needs = $needRepository->findAll();

        $data = array_map(function (Need $need) {
            return [
                'id' => $need->getId(),
                'name' => $need->getName(),
                'audiUser' => $need->getAudiUser(),
                'audiDate' => $need->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $need->getAudiAction(),
            ];
        }, $needs);

        return new JsonResponse($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $need = new Need();
        $need->setName($data['name'] ?? null);
        $need->setAudiUser($data['audi_user'] ?? null);
        $need->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $need->setAudiAction('I');

        $em->persist($need);
        $em->flush();

        return new JsonResponse(['message' => 'Necesidad creada.'], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, NeedRepository $needRepository): JsonResponse
    {
        $need = $needRepository->find($id);

        if (!$need) {
            return new JsonResponse(['error' => 'Necesidad no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $need->setName($data['name'] ?? $need->getName());
        $need->setAudiUser($data['audi_user'] ?? null);
        $need->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $need->setAudiAction('U');

        $em->flush();

        return new JsonResponse(['message' => 'Necesidad actualizada.']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, NeedRepository $needRepository): JsonResponse
    {
        $need = $needRepository->find($id);

        if (!$need) {
            return new JsonResponse(['error' => 'Necesidad no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $need->setAudiUser($data['audi_user'] ?? null);
        $need->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $need->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Necesidad marcada como eliminada.']);
    }
}
