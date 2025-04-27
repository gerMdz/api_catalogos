<?php

namespace App\Controller;

use App\Entity\Gender;
use App\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/gender')]
class GenderController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(GenderRepository $genderRepository): JsonResponse
    {
        $genders = $genderRepository->findAll();

        $data = array_map(function (Gender $gender) {
            return [
                'id' => $gender->getId(),
                'name' => $gender->getName(),
                'audiUser' => $gender->getAudiUser(),
                'audiDate' => $gender->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $gender->getAudiAction(),
            ];
        }, $genders);

        return new JsonResponse($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $gender = new Gender();
        $gender->setName($data['name'] ?? null);
        $gender->setAudiUser($data['audi_user'] ?? null);
        $gender->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $gender->setAudiAction('I');

        $em->persist($gender);
        $em->flush();

        return new JsonResponse(['message' => 'Género creado.'], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, GenderRepository $genderRepository): JsonResponse
    {
        $gender = $genderRepository->find($id);

        if (!$gender) {
            return new JsonResponse(['error' => 'Género no encontrado.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $gender->setName($data['name'] ?? $gender->getName());
        $gender->setAudiUser($data['audi_user'] ?? null);
        $gender->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $gender->setAudiAction('U');

        $em->flush();

        return new JsonResponse(['message' => 'Género actualizado.']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, GenderRepository $genderRepository): JsonResponse
    {
        $gender = $genderRepository->find($id);

        if (!$gender) {
            return new JsonResponse(['error' => 'Género no encontrado.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $gender->setAudiUser($data['audi_user'] ?? null);
        $gender->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $gender->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Género marcado como eliminado.']);
    }
}
