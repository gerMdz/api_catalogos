<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/experiences')]
class ExperienceController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(ExperienceRepository $experienceRepository): JsonResponse
    {
        $experiences = $experienceRepository->findAll();

        $data = array_map(function (Experience $experience) {
            return [
                'id' => $experience->getId(),
                'name' => $experience->getName(),
                'audiUser' => $experience->getAudiUser(),
                'audiDate' => $experience->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $experience->getAudiAction(),
            ];
        }, $experiences);

        return new JsonResponse($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $experience = new Experience();
        $experience->setName($data['name'] ?? null);
        $experience->setAudiUser($data['audi_user'] ?? null);
        $experience->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $experience->setAudiAction('I');

        $em->persist($experience);
        $em->flush();

        return new JsonResponse(['message' => 'Experiencia creada.'], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em, ExperienceRepository $experienceRepository): JsonResponse
    {
        $experience = $experienceRepository->find($id);

        if (!$experience) {
            return new JsonResponse(['error' => 'Experiencia no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $experience->setName($data['name'] ?? $experience->getName());
        $experience->setAudiUser($data['audi_user'] ?? null);
        $experience->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $experience->setAudiAction('U');

        $em->flush();

        return new JsonResponse(['message' => 'Experiencia actualizada.']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, ExperienceRepository $experienceRepository): JsonResponse
    {
        $experience = $experienceRepository->find($id);

        if (!$experience) {
            return new JsonResponse(['error' => 'Experiencia no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $experience->setAudiUser($data['audi_user'] ?? null);
        $experience->setAudiDate(new \DateTimeImmutable($data['audi_date'] ?? 'now'));
        $experience->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Experiencia marcada como eliminada.']);
    }
}
