<?php

namespace App\Controller;

use App\Entity\LifeStage;
use App\Repository\LifeStageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/lifestages')]
class LifeStageController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(LifeStageRepository $lifeStageRepository): JsonResponse
    {
        $stages = $lifeStageRepository->findAll();

        $data = array_map(function (LifeStage $stage) {
            return [
                'id' => $stage->getId(),
                'name' => $stage->getName(),
                'slug' => $stage->getSlug(),
                'audiUser' => $stage->getAudiUser(),
                'audiDate' => $stage->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $stage->getAudiAction(),
            ];
        }, $stages);

        return new JsonResponse($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $stage = new LifeStage();
        $stage->setName($data['name'] ?? null);
        $stage->setSlug($data['slug'] ?? null);
        $stage->setAudiUser($data['audi_user'] ?? null);
        $stage->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $stage->setAudiAction('I');

        $em->persist($stage);
        $em->flush();

        return new JsonResponse(['message' => 'Etapa de vida creada.'], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, LifeStageRepository $lifeStageRepository): JsonResponse
    {
        $stage = $lifeStageRepository->find($id);

        if (!$stage) {
            return new JsonResponse(['error' => 'Etapa de vida no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $stage->setName($data['name'] ?? $stage->getName());
        $stage->setSlug($data['slug'] ?? $stage->getSlug());
        $stage->setAudiUser($data['audi_user'] ?? null);
        $stage->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $stage->setAudiAction('U');

        $em->flush();

        return new JsonResponse(['message' => 'Etapa de vida actualizada.']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, LifeStageRepository $lifeStageRepository): JsonResponse
    {
        $stage = $lifeStageRepository->find($id);

        if (!$stage) {
            return new JsonResponse(['error' => 'Etapa de vida no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $stage->setAudiUser($data['audi_user'] ?? null);
        $stage->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $stage->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Etapa de vida marcada como eliminada.']);
    }
}
