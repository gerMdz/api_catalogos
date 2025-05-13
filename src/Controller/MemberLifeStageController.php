<?php

namespace App\Controller;

use App\Entity\MemberLifeStage;
use App\Entity\Member;
use App\Entity\LifeStage;
use App\Repository\MemberLifeStageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/member-life-stages')]
class MemberLifeStageController extends AbstractApiController
{


    #[Route('/', name: 'member_life_stage_list', methods: ['GET'])]
    public function list(Request $request, MemberLifeStageRepository $repo): JsonResponse
    {
        $incluirEliminados = $request->query->getBoolean('all', false);

        $items = $incluirEliminados
            ? $repo->findAllIncluyendoEliminados()
            : $repo->findAllActivos();


        return $this->json(array_map(fn(MemberLifeStage $item) => [
            'id' => $item->getId(),
            'audiUser' => $this->obtenerUsuarioPorAudiUser($item->getAudiUser()),
            'audiDate' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
            'audiAction' => $item->getAudiAction() ?? 'I',
            'member' => [
                'id' => $item->getMember()?->getId(),
                'nombre' => $item->getMember(),
                'dni' => $item->getMember()?->getDniDocument(),
            ],
            'lifeStage' => [
                'id' => $item->getLifeStage()?->getId(),
                'nombre' => $item->getLifeStage()?->getName(),
            ]
        ], $items));
    }


    #[Route('/member/{id}', name: 'member_life_stage_by_member', methods: ['GET'])]
    public function show(Member $member, MemberLifeStageRepository $repo): JsonResponse
    {
        $result = $repo->findByMember($member);

        return $this->json(array_map(fn(MemberLifeStage $item) => [
            'id' => $item->getId(),
            'lifeStage_id' => $item->getLifeStage()?->getId(),
            'lifeStage' => $item->getLifeStage()?->getName(),
            'audi_action' => $item->getAudiAction(),
            'audi_date' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
            'audi_user' => $item->getAudiUser(),
        ], $result));


    }

    /**
     * @throws ORMException
     */
    #[Route('/', name: 'member_life_stage_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $member = $em->getReference(Member::class, $data['member_id']);
        $lifeStage = $em->getReference(LifeStage::class, $data['life_stage_id']);

        $entity = new MemberLifeStage();
        $entity->setMember($member);
        $entity->setLifeStage($lifeStage);
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
    #[Route('/{id}', name: 'member_life_stage_update', methods: ['PUT'])]
    public function update(Request $request, MemberLifeStage $memberLifeStage, EntityManagerInterface $em): JsonResponse
    {


        $data = json_decode($request->getContent(), true);

        if (isset($data['life_stage_id'])) {
            $lifeStage = $em->getReference(LifeStage::class, $data['life_stage_id']);
            $memberLifeStage->setLifeStage($lifeStage);
        }


        $memberLifeStage->setAudiAction('U');
        $memberLifeStage->setAudiDate(new DateTime());
        $memberLifeStage->setAudiUser($this->getUser()?->getAuditId());


        $em->flush();


        return $this->json([
            'success' => true,
            'id' => $memberLifeStage->getId(),
            'audiDate' => $memberLifeStage->getAudiDate()?->format('Y-m-d H:i:s')
        ]);
    }


    #[Route('/{id}', name: 'member_life_stage_delete', methods: ['DELETE'])]
    public function delete(MemberLifeStage $memberLifeStage, EntityManagerInterface $em): JsonResponse
    {
        $memberLifeStage->setAudiAction('D');
        $memberLifeStage->setAudiDate(new DateTime());
        $memberLifeStage->setAudiUser($this->getUser()?->getAuditId());

        $em->flush();

        return $this->json(['success' => true]);
    }
}
