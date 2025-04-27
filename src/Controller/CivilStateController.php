<?php

namespace App\Controller;

use App\Entity\CivilState;
use App\Repository\CivilStateRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/civil_states')]
class CivilStateController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(CivilStateRepository $civilStateRepository): JsonResponse
    {
        $civilStates = $civilStateRepository->findAll();

        $data = array_map(function (CivilState $state) {
            return [
                'id' => $state->getId(),
                'name' => $state->getName(),
                'audiUser' => $state->getAudiUser(),
                'audiDate' => $state->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $state->getAudiAction(),
            ];
        }, $civilStates);

        return new JsonResponse($data);
    }

    /**
     * @throws \Exception
     */
    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $civilState = new CivilState();
        $civilState->setName($data['name'] ?? null);
        $civilState->setAudiUser($data['audi_user'] ?? null);
        $civilState->setAudiDate(new DateTimeImmutable($data['audi_date'] ?? 'now'));
        $civilState->setAudiAction('I');

        $em->persist($civilState);
        $em->flush();

        return new JsonResponse(['message' => 'Estado civil creado.'], 201);
    }

    /**
     * @throws \Exception
     */
    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, CivilStateRepository $civilStateRepository): JsonResponse
    {
        $civilState = $civilStateRepository->find($id);

        if (!$civilState) {
            return new JsonResponse(['error' => 'Estado civil no encontrado.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $civilState->setName($data['name'] ?? $civilState->getName());
        $civilState->setAudiUser($data['audi_user'] ?? null);
        $civilState->setAudiDate(new DateTimeImmutable($data['audi_date'] ?? 'now'));
        $civilState->setAudiAction('U');


        $em->flush();


        return new JsonResponse(['message' => 'Estado civil actualizado: ' ], 201);
    }

    /**
     * @throws \Exception
     */
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, CivilStateRepository $civilStateRepository): JsonResponse
    {
        $civilState = $civilStateRepository->find($id);

        if (!$civilState) {
            return new JsonResponse(['error' => 'Estado civil no encontrado.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $civilState->setAudiUser($data['audi_user'] ?? null);
        $civilState->setAudiDate(new DateTimeImmutable($data['audi_date'] ?? 'now'));
        $civilState->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Estado civil marcado como eliminado.']);
    }
}
