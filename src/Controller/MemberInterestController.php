<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\MemberInterest;
use App\Entity\UsuarioPanel;
use App\Repository\InterestRepository;
use App\Repository\MemberInterestRepository;
use App\Repository\MemberRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/member-interest')]
class MemberInterestController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/', name: 'member_interest_list', methods: ['GET'])]
    public function list(Request $request, MemberInterestRepository $repo): JsonResponse
    {
        $incluirEliminados = $request->query->getBoolean('all', false);

        $items = $incluirEliminados
            ? $repo->findAllIncluyendoEliminados()
            : $repo->findAllActivos();

        return $this->json(array_map(fn(MemberInterest $item) => [
            'id' => $item->getId(),
            'audiUser' => $this->obtenerUsuarioPorAudiUser($item->getAudiUser()),
            'audiDate' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
            'audiAction' => $item->getAudiAction() ?? 'I',
            'member' => [
                'id' => $item->getMember()?->getId(),
                'nombre' => $item->getMember(),
                'dni' => $item->getMember()?->getDniDocument(),
            ],
            'interest' => [
                'id' => $item->getInterest()?->getId(),
                'nombre' => $item->getInterest(),
            ]
        ], $items));
    }


    #[Route('/member/{memberId}', name: 'member_interest_by_member', methods: ['GET'])]
    public function show(int $memberId, MemberRepository $memberRepo, MemberInterestRepository $repo): JsonResponse
    {
        $member = $memberRepo->find($memberId);
        if (!$member) {
            return $this->json(['error' => 'Miembro no encontrado'], 404);
        }

        $items = $repo->findByMember($member);

        return $this->json(array_map(fn(MemberInterest $item) => [
            'id' => $item->getId(),
            'interest' => [
                'id' => $item->getId(),
                'interest_id' => $item->getInterest()?->getId(),
                'interest' => $item->getInterest()?->getName(),
                'audi_action' => $item->getAudiAction(),
                'audi_date' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
                'audi_user' => $item->getAudiUser(),
            ]
        ], $items));
    }

    #[Route('/', name: 'member_interest_create', methods: ['POST'])]
    public function create(
        Request                $request,
        EntityManagerInterface $em,
        MemberRepository       $memberRepo,
        InterestRepository     $interestRepo
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $member = $memberRepo->find($data['member_id'] ?? 0);
        $interest = $interestRepo->find($data['interest_id'] ?? 0);

        if (!$member || !$interest) {
            return $this->json(['error' => 'Datos inválidos'], 400);
        }

        $item = (new MemberInterest())
            ->setMember($member)
            ->setInterest($interest)
            ->setAudiUser($this->getUser()?->getAuditId())
            ->setAudiDate(new DateTime())
            ->setAudiAction('I');

        $em->persist($item);
        $em->flush();

        return $this->json(['success' => true, 'id' => $item->getId()]);
    }

    #[Route('/{id}', name: 'member_interest_update', methods: ['PUT'])]
    public function update(
        int                      $id,
        Request                  $request,
        EntityManagerInterface   $em,
        MemberInterestRepository $repo,
        MemberRepository         $memberRepo,
        InterestRepository       $interestRepo
    ): JsonResponse
    {
        $item = $repo->find($id);
        if (!$item || $item->getAudiAction() === 'D') {
            return $this->json(['error' => 'No encontrado'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['member_id'])) {
            $member = $memberRepo->find($data['member_id']);
            if ($member) $item->setMember($member);
        }

        if (isset($data['interest_id'])) {
            $interest = $interestRepo->find($data['interest_id']);
            if ($interest) $item->setInterest($interest);
        }

        $item
            ->setAudiUser($this->getUser()?->getAuditId())
            ->setAudiDate(new DateTime())
            ->setAudiAction('U');

        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'member_interest_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em, MemberInterestRepository $repo): JsonResponse
    {
        $item = $repo->find($id);
        if (!$item || $item->getAudiAction() === 'D') {
            return $this->json(['error' => 'No encontrado'], 404);
        }

        $item
            ->setAudiUser($this->getUser()?->getAuditId())
            ->setAudiDate(new DateTime())
            ->setAudiAction('D');

        $em->flush();

        return $this->json(['success' => true]);
    }

    public function obtenerUsuarioPorAudiUser(?int $id)
    {
        $usuario = null;
        if ($id) {
            $usuario = $this->em->getRepository(UsuarioPanel::class)->findOneBy(['auditId' => $id])->getNombre();
        }
        return $usuario;
    }
}
