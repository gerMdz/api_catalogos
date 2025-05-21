<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\MemberFamily;
use App\Repository\MemberFamilyRepository;
use App\Repository\FamilyRepository;
use App\Repository\MemberRepository;
use App\Service\LoggerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/member-family')]
class MemberFamilyController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em, private readonly LoggerService $logger)
    {
    }

    #[Route('', name: 'member_family_list', methods: ['GET'])]
    public function list(MemberFamilyRepository $repository): JsonResponse
    {
        $data = $repository->findAllActivos();
        return $this->getArrayMemberFamily($data);
    }

    #[Route('/member/{id}', name: 'member_family_show', methods: ['GET'])]
    public function show(int $id, MemberFamilyRepository $repository): JsonResponse
    {
        $data = $repository->findActivosByMember($id);
        return $this->getArrayMemberFamily($data);
    }

    #[Route('', name: 'member_family_create', methods: ['POST'])]
    public function create(
        Request                $request,
        MemberRepository       $memberRepo,
        FamilyRepository       $familyRepo,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $mf = new MemberFamily();
        $mf->setMember($memberRepo->find($data['member_id']));
        $mf->setRelatedMember($memberRepo->find($data['related_member_id']));
        $mf->setFamily($familyRepo->find($data['family_id']));
        $mf->setAsistChurch($data['asist_church'] ?? null);
        $mf->setCoexists($data['coexists'] ?? null);

        $mf->setAudiUser($this->getUser()->getAuditId());
        $mf->setAudiDate(new DateTime());
        $mf->setAudiAction('I');

        $em->persist($mf);
        $em->flush();

        return $this->json(['success' => true, 'id' => $mf->getId()]);
    }

    #[Route('/{id}', name: 'member_family_update', methods: ['PUT'])]
    public function update(
        int                    $id,
        Request                $request,
        MemberFamilyRepository $repository,
        FamilyRepository       $familyRepo,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $mf = $repository->find($id);

        if (!$mf) {
            return $this->json(['error' => 'No encontrado'], 404);
        }

        $mf->setRelatedMember($this->em->getRepository(Member::class)->find($data['related_member_id']));
        $mf->setFamily($familyRepo->find($data['family_id']));
        $mf->setAsistChurch($data['asist_church'] ?? null);
        $mf->setCoexists($data['coexists'] ?? null);

        $mf->setAudiUser($this->getUser()->getAuditId());
        $mf->setAudiDate(new DateTime());
        $mf->setAudiAction('U');

        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'member_family_delete', methods: ['DELETE'])]
    public function delete(int $id, MemberFamilyRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $mf = $repository->find($id);
        if (!$mf) {
            return $this->json(['error' => 'No encontrado'], 404);
        }

        $mf->setAudiUser($this->getUser()->getAuditId());
        $mf->setAudiDate(new DateTime());
        $mf->setAudiAction('D');

        $em->flush();

        return $this->json(['success' => true]);
    }


    public function getArrayMemberFamily(array $data): JsonResponse
    {
        $agrupados = [];

        /** @var MemberFamily $mf */
        foreach ($data as $mf) {

            if (!$mf->getFamily() || $mf->getFamily()->getId() === 0) {

                $this->logger->log(
                    'data_corruption',
                    'MemberFamily con family_id = 0',
                    [
                        'member_family_id' => $mf->getId(),
                        'member_id' => $mf->getMember()?->getId(),
                        'audi_user' => $mf->getAudiUser(),
                    ]
                );
                continue;
            }
            $key = $mf->getMember()->getId() . '-' . $mf->getFamily()->getId();

            if (!isset($agrupados[$key])) {
                $agrupados[$key] = [];
            }

            $agrupados[$key][] = $mf;
        }

        $resultadoFiltrado = [];

        foreach ($agrupados as $grupo) {

            $hayRelacionEspecifica = count(array_filter($grupo, fn($mf) => $mf->getRelatedMember() !== null)) > 0;


            if ($hayRelacionEspecifica) {
                // Guardar solo los que tienen relatedMember definido
                foreach ($grupo as $mf) {
                    if ($mf->getRelatedMember()) {
                        $resultadoFiltrado[] = $mf;
                    }
                }
            } else {
                // No hay ninguno con relatedMember => conservar solo el primero (genérico)
                $resultadoFiltrado[] = $grupo[0];
            }
        }

        // Transformar a DTO como antes
        $result = array_map(function (MemberFamily $mf) {
            $related = $mf->getRelatedMember();
            return [
                'id' => $mf->getId(),
                'member' => [
                    'id' => $mf->getMember()->getId(),
                    'name' => $mf->getMember()->getName(),
                    'lastname' => $mf->getMember()->getLastname(),
                ],
                'related_member' => $related ? [
                    'id' => $related->getId(),
                    'name' => $related->getName(),
                    'lastname' => $related->getLastname(),
                    'dniDocument' => $related->getDniDocument(),
                ] : null,
                'family' => $mf->getFamily()?->getName(),
                'family_id' => $mf->getFamily()?->getId(),
                'asist_church' => $mf->getAsistChurch(),
                'coexists' => $mf->getCoexists(),
                'audiUser' => $mf->getAudiUser(),
                'audiDate' => $mf->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiAction' => $mf->getAudiAction() ?? 'I',
            ];
        }, $resultadoFiltrado);

        return $this->json($result);
    }

}
