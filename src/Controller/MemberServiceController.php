<?php

namespace App\Controller;

use App\Entity\MemberService;
use App\Repository\MemberServiceRepository;
use App\Entity\Member;
use App\Entity\Service;
use App\Service\AudiHelperService;
use App\Service\LoggerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/member-service')]
class MemberServiceController extends AbstractApiController
{


    #[Route('/', name: 'member_service_list', methods: ['GET'])]
    public function list(Request $request, MemberServiceRepository $repo): JsonResponse
    {
        $incluirEliminados = $request->query->getBoolean('all', false);

        $items = $incluirEliminados
            ? $repo->findAllIncluyendoEliminados()
            : $repo->findAllActive();

        $response = array_map(function (MemberService $item) {
            if (!$item->getService() || $item->getService()->getId() === 0) {
                $this->logger->log(
                    'data_corruption',
                    'MemberService con service_id = 0',
                    [
                        'member_service_id' => $item->getId(),
                        'member_id' => $item->getMember()?->getId(),
                        'audi_user' => $item->getAudiUser(),
                    ]
                );
                $nameService = 'No indicado';
                $idService = 0;
            } else {

                $nameService = $item->getService()?->getName();
                $idService = $item->getService()->getId();
            }

            return [
                'id' => $item->getId(),
                'audiUser' => $this->obtenerUsuarioPorAudiUser($item->getAudiUser()),
                'audiDate' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $item->getAudiAction() ?? 'I',
                'member' => [
                    'id' => $item->getMember()?->getId(),
                    'nombre' => $item->getMember()?->getNombreCompletoConDni(),
                ],
                'service' => [
                    'id' => $idService,
                    'nombre' => $nameService,
                ]
            ];
        }, $items);

        return $this->json($response);
    }


    #[Route('/{memberId}', name: 'member_service_show', methods: ['GET'])]
    public function show(int $memberId, MemberServiceRepository $repo): JsonResponse
    {
        $data = $repo->findByMember($memberId);
        $result = [];

        foreach ($data as $ms) {
            $result[] = [
                'id' => $ms->getId(),
                'member' => $ms->getMember()?->getNombreCompletoConDni(),
                'service' => $ms->getService()?->getName(),
            ];
        }

        return $this->json($result);
    }

    #[Route('', name: 'member_service_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $member = $em->getRepository(Member::class)->find($data['member_id']);
        $service = $em->getRepository(Service::class)->find($data['service_id']);

        if (!$member || !$service) {
            return $this->json(['error' => 'Datos inválidos'], 400);
        }

        $entity = new MemberService();
        $entity->setMember($member);
        $entity->setService($service);
        $entity->setAudiUser($this->getUser()?->getAuditId());
        $entity->setAudiDate(new DateTime());
        $entity->setAudiAction('I');

        $em->persist($entity);
        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'member_service_update', methods: ['PUT'])]
    public function update(int $id, Request $request, MemberServiceRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $entity = $repo->findOneActiveById($id);
        if (!$entity) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $service = $em->getRepository(Service::class)->find($data['service'] ?? null);
        if ($service) {
            $entity->setService($service);
        }

        $entity->setAudiUser($this->getUser()?->getAuditId());
        $entity->setAudiDate(new DateTime());
        $entity->setAudiAction('U');

        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'member_service_delete', methods: ['DELETE'])]
    public function delete(int $id, MemberServiceRepository $repo, EntityManagerInterface $em): JsonResponse
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
