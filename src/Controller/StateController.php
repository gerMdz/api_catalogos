<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\State;
use App\Repository\StateRepository;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/states')]
class StateController extends AbstractApiController
{
    #[Route('/', name: 'app_state_index', methods: ['GET'])]
    public function index(Request $request, StateRepository $repo): JsonResponse
    {
        $incluirEliminados = $request->query->getBoolean('all');

        $items = $incluirEliminados
            ? $repo->findAll()
            : $repo->findAllActive();

        return $this->json(array_map(fn(State $item) => [
            'id' => $item->getId(),
            'audiUser' => $this->obtenerUsuarioPorAudiUser($item->getAudiUser()),
            'audiDate' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
            'audiAction' => $item->getAudiAction() ?? 'I',
            'nombre' => $item->getName(),
            'pais' => $item->getCountry()->getName(),
        ], $items));
    }

    #[Route('', name: 'app_state_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $state = $data['state'];
        $countryId = $data['country_id'];

        if (!$state || !$countryId) {
            return $this->json(['error' => 'Debe indicar provincia y país'], 400);
        }

        $country = $this->entityManager->getRepository(Country::class)->find($countryId);

        if (!$country) {
            return $this->json(['error' => 'El país no se encuentra'], 404);
        }

        $entity = new State();

        $entity->setName($state)
            ->setCountry($country)
            ->setAudiUser($this->getUser()?->getAuditId())
            ->setAudiDate(new DateTime())
            ->setAudiAction('I');

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'app_state_show', methods: ['GET'])]
    public function show(int $id, StateRepository $repo): JsonResponse
    {
        $state = $repo->find($id);
        if (!$state) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }

        $result[] = [
            'id' => $state->getId(),
            'nombre' => $state->getName(),
            'pais' => $state->getCountry()->getName(),
            'audiUser' => $state->getAudiUser(),
            'audiDate' => $state->getAudiDate()?->format('d-m-Y H:i:s'),
            'audiAction' => $state->getAudiAction(),

        ];

        return $this->json($result);
    }

    #[Route('/{id}', name: 'app_state_edit', methods: ['PUT'])]
    public function update(Request         $request, int $id,
                           StateRepository $repository): JsonResponse
    {
        $entidad = $repository->find($id);
        if (!$entidad) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }
        $data = json_decode($request->getContent(), true);
        $state = $data['state'];
        $countryId = $data['country_id'];

        if (!$state || !$countryId) {
            return $this->json(['error' => 'Debe indicar provincia y país'], 400);
        }

        $country = $this->entityManager->getRepository(Country::class)->find($countryId);

        if (!$country) {
            return $this->json(['error' => 'No se encontró el país indicado'], 400);
        }

        $entidad->setName($state)
            ->setCountry($country)
            ->setAudiUser($this->getUser()?->getAuditId())
            ->setAudiDate(new DateTime())
            ->setAudiAction('U');

        $this->entityManager->flush();

        return $this->json(['success' => true]);

    }

    #[Route('/{id}', name: 'app_state_delete', methods: ['POST'])]
    public function delete(int $id, StateRepository $repository): JsonResponse
    {
        $entity = $repository->find($id);
        if (!$entity) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }

        $this->destroy($entity);

        return $this->json(['success' => true]);
    }


}
