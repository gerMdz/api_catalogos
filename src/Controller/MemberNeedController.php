<?php

namespace App\Controller;

use App\Entity\MemberNeed;
use App\Entity\Member;
use App\Entity\Need;
use App\Repository\MemberNeedRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/member-need')]
class MemberNeedController extends AbstractApiController
{


    #[Route('/', name: 'member_need_list', methods: ['GET'])]
    public function list(Request $request, MemberNeedRepository $repo): JsonResponse
    {
        $incluirEliminados = $request->query->getBoolean('all', false);

        $items = $incluirEliminados
            ? $repo->findAllIncluyendoEliminados()
            : $repo->findAllActivos();


        return $this->json(array_map(fn(MemberNeed $item) => [
            'id' => $item->getId(),
            'audiUser' => $this->obtenerUsuarioPorAudiUser($item->getAudiUser()),
            'audiDate' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
            'audiAction' => $item->getAudiAction() ?? 'I',
            'member' => [
                'id' => $item->getMember()?->getId(),
                'nombre' => trim($item->getMember()?->getName() . ' ' . $item->getMember()?->getLastname() . ' (' . $item->getMember()?->getDniDocument() . ')'),
                'dni' => $item->getMember()?->getDniDocument(),
            ],
            'need' => [
                'id' => $item->getNeed()?->getId(),
                'nombre' => $item->getNeed()?->getName(),
            ]
        ], $items));
    }


    #[Route('/member/{id}', name: 'member_need_by_member', methods: ['GET'])]
    public function show(Member $member, MemberNeedRepository $repo): JsonResponse
    {
        $result = $repo->findByMember($member);

        return $this->json(array_map(fn(MemberNeed $item) => [
            'id' => $item->getId(),
            'need_id' => $item->getNeed()?->getId(),
            'need' => $item->getNeed()?->getName(),
            'audi_action' => $item->getAudiAction(),
            'audi_date' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
            'audi_user' => $item->getAudiUser(),
        ], $result));
    }

    /**
     * @throws ORMException
     */
    #[Route('/', name: 'member_need_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $member = $em->getReference(Member::class, $data['member_id']);
        $need = $em->getReference(Need::class, $data['need_id']);

        $entity = new MemberNeed();
        $entity->setMember($member);
        $entity->setNeed($need);
        $entity->setAudiAction('I');
        $entity->setAudiDate(new DateTime());
        $entity->setAudiUser($this->getUser()?->getAuditId());

        $em->persist($entity);
        $em->flush();

        return $this->json(['success' => true, 'id' => $entity->getId()], Response::HTTP_CREATED);
    }

    /**
     * @throws ORMException
     */
    #[Route('/{id}', name: 'member_need_update', methods: ['PUT'])]
    public function update(Request $request, MemberNeed $memberNeed, EntityManagerInterface $em): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        if (isset($data['need_id'])) {
            $need = $em->getReference(Need::class, $data['need_id']);
            $memberNeed->setNeed($need);
        }


        $memberNeed->setAudiAction('U');
        $memberNeed->setAudiDate(new DateTime());
        $memberNeed->setAudiUser($this->getUser()?->getAuditId());


        $em->flush();


        return $this->json([
            'success' => true,
            'id' => $memberNeed->getId(),
            'audiDate' => $memberNeed->getAudiDate()?->format('Y-m-d H:i:s')
        ]);
    }


    #[Route('/{id}', name: 'member_need_delete', methods: ['DELETE'])]
    public function delete(MemberNeed $memberNeed, EntityManagerInterface $em): JsonResponse
    {
        $memberNeed->setAudiAction('D');
        $memberNeed->setAudiDate(new DateTime());
        $memberNeed->setAudiUser($this->getUser()?->getAuditId());

        $em->flush();

        return $this->json(['success' => true]);
    }
}
