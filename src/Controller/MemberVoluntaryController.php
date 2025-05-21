<?php

namespace App\Controller;

use App\Entity\MemberVoluntary;
use App\Repository\MemberVoluntaryRepository;
use App\Entity\Member;
use App\Entity\Voluntary;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

#[Route('/api/member-voluntary')]
class MemberVoluntaryController extends AbstractApiController
{
    #[Route('/', name: 'member_voluntary_list', methods: ['GET'])]
    public function list(Request $request, MemberVoluntaryRepository $repo): JsonResponse
    {
        $incluirEliminados = $request->query->getBoolean('all', false);

        $items = $incluirEliminados
            ? $repo->findAllIncluyendoEliminados()
            : $repo->findAllActive();

        $response = array_map(function (MemberVoluntary $item) {

            $nameVoluntary = $this->safeGetEntity(
                fn() => $item->getVoluntary()?->getName(),
                'Voluntary.getName() no accesible',
                [
                    'member_voluntary_id' => $item->getId(),
                    'member_id' => $item->getMember()?->getId(),
                    'audi_user' => $item->getAudiUser(),
                ]
            ) ?? 'No indicado';

            $idVoluntary = $this->safeGetEntity(
                fn() => $item->getVoluntary()?->getId(),
                'Voluntary.getId() no accesible',
                [
                    'member_voluntary_id' => $item->getId(),
                    'member_id' => $item->getMember()?->getId(),
                    'audi_user' => $item->getAudiUser(),
                ]
            ) ?? 0;

            return [
                'id' => $item->getId(),
                'audiUser' => $this->obtenerUsuarioPorAudiUser($item->getAudiUser()),
                'audiDate' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $item->getAudiAction() ?? 'I',
                'member' => [
                    'id' => $item->getMember()?->getId(),
                    'nombre' => $item->getMember()?->getNombreCompletoConDni(),
                ],
                'voluntary' => [
                    'id' => $idVoluntary,
                    'nombre' => $nameVoluntary,
                ],
            ];
        }, $items);


        return $this->json($response);


    }


    #[Route('/{memberId}', name: 'member_voluntary_show', methods: ['GET'])]
    public function show(int $memberId, MemberVoluntaryRepository $repo): JsonResponse
    {
        $data = $repo->findByMember($memberId);
        $result = [];

        foreach ($data as $v) {
            $result[] = [
                'id' => $v->getId(),
                'member' => $v->getMember()?->getNombreCompletoConDni(),
                'voluntary' => $v->getVoluntary()?->getName(),
                'service' => $v->getService()
            ];
        }

        return $this->json($result);
    }

    #[Route('', name: 'member_voluntary_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $member = $em->getRepository(Member::class)->find($data['member']);
        $voluntary = $em->getRepository(Voluntary::class)->find($data['voluntary']);

        if (!$member || !$voluntary) {
            return $this->json(['error' => 'Datos inválidos'], 400);
        }

        $entity = new MemberVoluntary();
        $entity->setMember($member);
        $entity->setVoluntary($voluntary);
        $entity->setService($data['service']);
        $entity->setAudiUser($this->getUser()?->getAuditId());
        $entity->setAudiDate(new DateTime());
        $entity->setAudiAction('I');

        $em->persist($entity);
        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'member_voluntary_update', methods: ['PUT'])]
    public function update(int $id, Request $request, MemberVoluntaryRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $entity = $repo->findOneActiveById($id);
        if (!$entity) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $voluntary = $em->getRepository(Voluntary::class)->find($data['voluntary'] ?? null);
        if ($voluntary) {
            $entity->setVoluntary($voluntary);
        }
        if (isset($data['service'])) {
            $entity->setService($data['service']);
        }

        $entity->setAudiUser($this->getUser()?->getAuditId());
        $entity->setAudiDate(new DateTime());
        $entity->setAudiAction('U');

        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'member_voluntary_delete', methods: ['DELETE'])]
    public function delete(int $id, MemberVoluntaryRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $entity = $repo->findOneActiveById($id);
        if (!$entity) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }

        $entity->setAudiUser($this->getUser()?->getAuditId());
        $entity->setAudiDate(new DateTime());
        $entity->setAudiAction('D');

        $em->flush();

        return $this->json(['success' => true]);
    }
}
