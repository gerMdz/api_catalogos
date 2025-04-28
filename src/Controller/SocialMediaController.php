<?php

namespace App\Controller;

use App\Entity\SocialMedia;
use App\Entity\UsuarioPanel;
use App\Repository\SocialMediaRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/social-media', name: 'api_social_media_')]
class SocialMediaController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(SocialMediaRepository $repository): JsonResponse
    {
        $socialMedias = $repository->findBy([], ['name' => 'ASC']);
        return $this->json($socialMedias);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        $socialMedia = new SocialMedia();
        $socialMedia->setName($data['name'] ?? null);
        $socialMedia->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $socialMedia->setAudiDate(new DateTime());
        $socialMedia->setAudiAction('I');

        $em->persist($socialMedia);
        $em->flush();

        return $this->json($socialMedia);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, SocialMedia $socialMedia, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $socialMedia->setName($data['name'] ?? $socialMedia->getName());
        $socialMedia->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $socialMedia->setAudiDate(new DateTime());
        $socialMedia->setAudiAction('U');

        $em->flush();

        return $this->json($socialMedia);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request, SocialMediaRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $socialMedia = $repository->find($id);

        if (!$socialMedia) {
            return new JsonResponse(['error' => 'Red social no encontrada.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $socialMedia->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $socialMedia->setAudiDate(new DateTimeImmutable($data['audi_date'] ?? 'now'));
        $socialMedia->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Red social marcada como eliminada.']);
    }
}
