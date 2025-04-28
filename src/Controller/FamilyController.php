<?php

namespace App\Controller;

use App\Entity\Family;
use App\Repository\FamilyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/family')]
class FamilyController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(FamilyRepository $familyRepository): JsonResponse
    {
        $families = $familyRepository->findAll();

        $data = array_map(function (Family $family) {
            return [
                'id' => $family->getId(),
                'name' => $family->getName(),
                'audiUser' => $family->getAudiUser(),
                'audiDate' => $family->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $family->getAudiAction(),
            ];
        }, $families);

        return new JsonResponse($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $family = new Family();
        $family->setName($data['name'] ?? null);
        $family->setDescription($data['description'] ?? null);
        $family->setAudiUser($data['audi_user'] ?? null);
        $family->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $family->setAudiAction('I');

        $em->persist($family);
        $em->flush();

        return new JsonResponse(['message' => 'Grupo familiar creado.'], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, FamilyRepository $familyRepository): JsonResponse
    {
        $family = $familyRepository->find($id);

        if (!$family) {
            return new JsonResponse(['error' => 'Grupo familiar no encontrado.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $family->setName($data['name'] ?? $family->getName());
        $family->setDescription($data['description'] ?? $family->getDescription());
        $family->setAudiUser($data['audi_user'] ?? null);
        $family->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $family->setAudiAction('U');

        $em->flush();

        return new JsonResponse(['message' => 'Grupo familiar actualizado.']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, FamilyRepository $familyRepository): JsonResponse
    {
        $family = $familyRepository->find($id);

        if (!$family) {
            return new JsonResponse(['error' => 'Grupo familiar no encontrado.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $family->setAudiUser($data['audi_user'] ?? null);
        $family->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $family->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Grupo familiar marcado como eliminado.']);
    }
}
