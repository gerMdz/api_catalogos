<?php

namespace App\Controller;

use App\Entity\MembersEnjoys;
use App\Entity\Enjoy;
use App\Repository\MembersEnjoysRepository;
use App\Repository\MemberRepository;
use App\Repository\EnjoyRepository;
use App\Service\LoggerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/members-enjoys')]
class MembersEnjoysController extends AbstractController
{
    public function __construct(
        private readonly LoggerService $logger,
        private readonly MembersEnjoysRepository $repository
    ) {
    }

    #[Route('/', name: 'members_enjoys_list', methods: ['GET'])]
    public function list(MembersEnjoysRepository $repository): JsonResponse
    {
        $data = $repository->findAll();

        $response = array_map(function (MembersEnjoys $mej) {
            if (!$mej->getEnjoy() || $mej->getEnjoy()->getId() === 0) {
                $this->logger->log(
                    'data_corruption',
                    'MembersEnjoys con enjoy_id = 0',
                    [
                        'members_enjoys_id' => $mej->getId(),
                        'member_id' => $mej->getMember()?->getId(),
                        'audi_user' => method_exists($mej, 'getAudiUser') ? $mej->getAudiUser() : null,
                    ]
                );
                $enjoy = 0;
            } else {
                $enjoy = $mej->getEnjoy();
            }

            return [
                'id' => $mej->getId(),
                'member_id' => $mej->getMember()?->getId(),
                'member' => $mej->getMember(),
                'enjoy_id' => $mej->getEnjoy()?->getId(),
                'enjoy' => $enjoy,
                'audi_action' => method_exists($mej, 'getAudiAction') ? $mej->getAudiAction() : null,
                'audi_date' => method_exists($mej, 'getAudiDate') ? ($mej->getAudiDate()?->format('Y-m-d H:i:s')) : null,
                'audi_user' => method_exists($mej, 'getAudiUser') ? $mej->getAudiUser() : null,
            ];
        }, $data);

        return $this->json($response);
    }

    #[Route('/{id}', name: 'members_enjoys_show', methods: ['GET'])]
    public function show(int $id, MembersEnjoysRepository $repository): JsonResponse
    {
        $mej = $repository->find($id);
        if (!$mej) {
            return $this->json(['error' => 'Relación no encontrada'], 404);
        }

        return $this->json([
            'id' => $mej->getId(),
            'member_id' => $mej->getMember()?->getId(),
            'member' => $mej->getMember(),
            'enjoy_id' => $mej->getEnjoy()?->getId(),
            'enjoy' => $mej->getEnjoy(),
            'audi_action' => method_exists($mej, 'getAudiAction') ? $mej->getAudiAction() : null,
            'audi_date' => method_exists($mej, 'getAudiDate') ? ($mej->getAudiDate()?->format('Y-m-d H:i:s')) : null,
            'audi_user' => method_exists($mej, 'getAudiUser') ? $mej->getAudiUser() : null,
        ]);
    }

    #[Route('/member/{memberId}', name: 'members_enjoys_by_member', methods: ['GET'])]
    public function showByMember(int $memberId, MembersEnjoysRepository $repository): JsonResponse
    {
        $data = $repository->findByMemberId($memberId);

        $response = array_map(function (MembersEnjoys $mej) {
            return [
                'id' => $mej->getId(),
                'enjoy_id' => $mej->getEnjoy()?->getId(),
                'enjoy' => $mej->getEnjoy()?->getName(),
                'audi_action' => method_exists($mej, 'getAudiAction') ? $mej->getAudiAction() : null,
                'audi_date' => method_exists($mej, 'getAudiDate') ? ($mej->getAudiDate()?->format('Y-m-d H:i:s')) : null,
                'audi_user' => method_exists($mej, 'getAudiUser') ? $mej->getAudiUser() : null,
            ];
        }, $data);

        return $this->json($response);
    }

    #[Route('/', name: 'members_enjoys_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        MemberRepository $memberRepo,
        EnjoyRepository $enjoyRepo,
        MembersEnjoysRepository $membersEnjoysRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $member = $memberRepo->find($data['member_id'] ?? 0);
        $enjoy = $enjoyRepo->find($data['enjoy_id'] ?? 0);

        if (!$member || !$enjoy) {
            return $this->json(['error' => 'Miembro o disfrute no encontrado'], 400);
        }

        // Verificar duplicado
        $existing = $membersEnjoysRepo->findOneBy([
            'member' => $member,
            'enjoy' => $enjoy,
        ]);

        if ($existing) {
            return $this->json([
                'error' => 'Este disfrute ya está asignado a este miembro.',
                'duplicate' => true,
            ], 409);
        }

        $mej = new MembersEnjoys();
        $mej->setMember($member);
        $mej->setEnjoy($enjoy);
        if (method_exists($mej, 'setAudiAction')) {
            $mej->setAudiAction('I');
        }
        if (method_exists($mej, 'setAudiDate')) {
            $mej->setAudiDate(new DateTime());
        }
        if (method_exists($this->getUser(), 'getAuditId') && method_exists($mej, 'setAudiUser')) {
            $mej->setAudiUser($this->getUser()->getAuditId());
        }

        $em->persist($mej);
        $em->flush();

        return $this->json(['success' => true, 'id' => $mej->getId()]);
    }

    #[Route('/{id}', name: 'members_enjoys_update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        MembersEnjoysRepository $repository,
        EntityManagerInterface $em,
        MemberRepository $memberRepo,
        EnjoyRepository $enjoyRepo
    ): JsonResponse {
        $mej = $repository->find($id);
        if (!$mej) {
            return $this->json(['error' => 'Relación no encontrada'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $newMember = $mej->getMember();
        $newEnjoy = $mej->getEnjoy();

        if (isset($data['member_id'])) {
            $member = $memberRepo->find($data['member_id']);
            if ($member) {
                $newMember = $member;
            }
        }

        if (isset($data['enjoy_id'])) {
            $enjoy = $enjoyRepo->find($data['enjoy_id']);
            if ($enjoy) {
                $newEnjoy = $enjoy;
            }
        }

        // Verificar duplicado excluyendo el actual
        $existing = $repository->findOneBy([
            'member' => $newMember,
            'enjoy' => $newEnjoy,
        ]);

        if ($existing && $existing->getId() !== $mej->getId()) {
            return $this->json([
                'error' => 'Este disfrute ya está asignado a este miembro.',
                'duplicate' => true,
            ], 409);
        }

        $mej->setMember($newMember);
        $mej->setEnjoy($newEnjoy);
        if (method_exists($mej, 'setAudiAction')) {
            $mej->setAudiAction('U');
        }
        if (method_exists($mej, 'setAudiDate')) {
            $mej->setAudiDate(new DateTime());
        }
        if (method_exists($this->getUser(), 'getAuditId') && method_exists($mej, 'setAudiUser')) {
            $mej->setAudiUser($this->getUser()->getAuditId());
        }

        $em->persist($mej);
        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'members_enjoys_delete', methods: ['DELETE'])]
    public function delete(int $id, MembersEnjoysRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $mej = $repository->find($id);
        if (!$mej) {
            return $this->json(['error' => 'Relación no encontrada'], 404);
        }

        if (method_exists($mej, 'setAudiAction')) {
            $mej->setAudiAction('D');
        }
        if (method_exists($mej, 'setAudiDate')) {
            $mej->setAudiDate(new DateTime());
        }
        if (method_exists($this->getUser(), 'getAuditId') && method_exists($mej, 'setAudiUser')) {
            $mej->setAudiUser($this->getUser()->getAuditId());
        }

        $em->flush();

        return $this->json(['success' => true]);
    }
}
