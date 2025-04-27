<?php

namespace App\Controller;

use App\Entity\Interest;
use App\Repository\InterestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/interests')]
class InterestController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(InterestRepository $interestRepository): JsonResponse
    {
        $interests = $interestRepository->findAll();

        $data = array_map(function (Interest $interest) {
            return [
                'id' => $interest->getId(),
                'name' => $interest->getName(),
                'audiUser' => $interest->getAudiUser(),
                'audiDate' => $interest->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $interest->getAudiAction(),
            ];
        }, $interests);

        return new JsonResponse($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $interest = new Interest();
        $interest->setName($data['name'] ?? null);
        $interest->setAudiUser($data['audi_user'] ?? null);
        $interest->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $interest->setAudiAction('I');

        $em->persist($interest);
        $em->flush();

        return new JsonResponse(['message' => 'Área de interés creada.'], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, InterestRepository $interestRepository): JsonResponse
    {
        $interest = $interestRepository->find($id);

        if (!$interest) {
            return new JsonResponse(['error' => 'Área de interés no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $interest->setName($data['name'] ?? $interest->getName());
        $interest->setAudiUser($data['audi_user'] ?? null);
        $interest->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $interest->setAudiAction('U');

        $em->flush();

        return new JsonResponse(['message' => 'Área de interés actualizada.']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, InterestRepository $interestRepository): JsonResponse
    {
        $interest = $interestRepository->find($id);

        if (!$interest) {
            return new JsonResponse(['error' => 'Área de interés no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $interest->setAudiUser($data['audi_user'] ?? null);
        $interest->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $interest->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Área de interés marcada como eliminada.']);
    }
}
