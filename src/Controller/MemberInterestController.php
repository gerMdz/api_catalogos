<?php

namespace App\Controller;

use App\Entity\MemberInterest;
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
    #[Route('/', name: 'member_interest_list', methods: ['GET'])]
    public function list(MemberInterestRepository $repo): JsonResponse
    {
        $items = $repo->findAllActive();

        return $this->json(array_map(fn(MemberInterest $item) => [
            'id' => $item->getId(),
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

    #[Route('/{memberId}', name: 'member_interest_by_member', methods: ['GET'])]
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
                'id' => $item->getInterest()?->getId(),
                'nombre' => $item->getInterest()??null,
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
}
