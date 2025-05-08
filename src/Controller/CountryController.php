<?php

namespace App\Controller;

use App\Entity\Country;
use App\Repository\CountryRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/countries')]
class CountryController extends AbstractApiController
{
    #[Route('/', name: 'app_country_index', methods: ['GET'])]
    public function index(Request $request, CountryRepository $repo): JsonResponse
    {
        $incluirEliminados = $request->query->getBoolean('all');

        $items = $incluirEliminados
            ? $repo->findAll()
            : $repo->findAllActive();

        return $this->json(array_map(fn(Country $item) => [
            'id' => $item->getId(),
            'audiUser' => $this->obtenerUsuarioPorAudiUser($item->getAudiUser()),
            'audiDate' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
            'audiAction' => $item->getAudiAction() ?? 'I',
            'nombre' => $item->getName(),
        ], $items));
    }

    #[Route('', name: 'app_country_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $country = $data['country'];

        if (!$country) {
            return $this->json(['error' => 'Debe indicar país'], 400);
        }
        $entity = new Country();

        $entity->setName($country)
            ->setAudiUser($this->getUser()?->getAuditId())
            ->setAudiDate(new DateTime())
            ->setAudiAction('I');

        $entityManager->persist($entity);
        $entityManager->flush();


        return $this->json(['success' => true]);

    }

    #[Route('/{id}', name: 'app_country_show', methods: ['GET'])]
    public function show(int $id, CountryRepository $repo): JsonResponse
    {
        $country = $repo->find($id);
        if (!$country) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }

        $result[] = [
            'id' => $country->getId(),
            'nombre' => $country->getName(),
            'audiUser' => $country->getAudiUser(),
            'audiDate' => $country->getAudiDate()?->format('d-m-Y H:i:s'),
            'audiAction' => $country->getAudiAction(),

        ];

        return $this->json($result);
    }

    #[Route('/{id}', name: 'app_country_edit', methods: ['PUT'])]
    public function update(Request $request, int $id, EntityManagerInterface $entityManager, CountryRepository $repository): JsonResponse
    {
        $entidad = $repository->find($id);
        if (!$entidad) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }
        $data = json_decode($request->getContent(), true);
        $country = $data['country'];

        if (!$country) {
            return $this->json(['error' => 'Debe indicar país'], 400);
        }


        $entidad->setName($country)
            ->setAudiUser($this->getUser()?->getAuditId())
            ->setAudiDate(new DateTime())
            ->setAudiAction('U');

        $entityManager->flush();

        return $this->json(['success' => true]);

    }

    #[Route('/{id}', name: 'app_country_delete', methods: ['POST'])]
    public function delete(int $id, EntityManagerInterface $entityManager, CountryRepository $repository): JsonResponse
    {
        $entity = $repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }

        $entity->setAudiUser($this->getUser()?->getAuditId());
        $entity->setAudiDate(new DateTime());
        $entity->setAudiAction('D');

        $entityManager->flush();

        return $this->json(['success' => true]);
    }
}
