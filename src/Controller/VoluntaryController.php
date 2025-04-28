<?php

namespace App\Controller;

use App\Entity\Voluntary;
use App\Repository\VoluntaryRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/voluntary', name: 'api_voluntary_')]
class VoluntaryController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(VoluntaryRepository $repository): JsonResponse
    {
        $voluntaries = $repository->findBy([], ['name' => 'ASC']);
        return $this->json($voluntaries);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $voluntary = new Voluntary();
        $voluntary->setName($data['name'] ?? null);
        $voluntary->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $voluntary->setAudiDate(new DateTime());
        $voluntary->setAudiAction('I');

        $em->persist($voluntary);
        $em->flush();

        return $this->json($voluntary);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, Voluntary $voluntary, EntityManagerInterface $em): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $voluntary->setName($data['name'] ?? $voluntary->getName());
        $voluntary->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $voluntary->setAudiDate(new DateTime());
        $voluntary->setAudiAction('U');

        $em->flush();

        return $this->json($voluntary);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, Request $request, VoluntaryRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $voluntary = $repository->find($id);

        if (!$voluntary) {
            return new JsonResponse(['error' => 'Voluntariado no encontrado.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $voluntary->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $voluntary->setAudiDate(new DateTimeImmutable($data['audi_date'] ?? 'now'));
        $voluntary->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Voluntariado marcado como eliminado.']);
    }
}
