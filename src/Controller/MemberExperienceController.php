<?php

namespace App\Controller;

use App\Entity\MemberExperience;
use App\Entity\MemberInterest;
use App\Repository\MemberExperienceRepository;
use App\Repository\MemberRepository;
use App\Repository\ExperienceRepository;
use App\Service\LoggerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/member-experience')]
class MemberExperienceController extends AbstractController
{

    public function __construct(
        private readonly LoggerService              $logger,
        private readonly MemberExperienceRepository $repository
    )
    {
    }


    #[Route('/', name: 'member_experience_list', methods: ['GET'])]
    public function list(MemberExperienceRepository $repository): JsonResponse
    {
        $data = $repository->findAll();

        $response = array_map(function (MemberExperience $me) {
            if (!$me->getExperience() || $me->getExperience()->getId() === 0) {
                $this->logger->log(
                    'data_corruption',
                    'MemberExperience con experience_id = 0',
                    [
                        'member_experience_id' => $me->getId(),
                        'member_id' => $me->getMember()?->getId(),
                        'audi_user' => $me->getAudiUser(),
                    ]
                );
                $experience = 0;
            } else {
                $experience = $me->getExperience();
            }


            return [
                'id' => $me->getId(),
                'member_id' => $me->getMember()?->getId(),
                'member' => $me->getMember(),
                'experience_id' => $me->getExperience()?->getId(),
                'experience' => $experience,
                'audi_action' => $me->getAudiAction(),
                'audi_date' => $me->getAudiDate()?->format('Y-m-d H:i:s'),
                'audi_user' => $me->getAudiUser(),
            ];
        }, $data);

        return $this->json($response);
    }


    #[Route('/{id}', name: 'member_experience_show', methods: ['GET'])]
    public function show(int $id, MemberExperienceRepository $repository): JsonResponse
    {
        $me = $repository->find($id);
        if (!$me) {
            return $this->json(['error' => 'Relación no encontrada'], 404);
        }

        return $this->json([
            'id' => $me->getId(),
            'member_id' => $me->getMember()?->getId(),
            'member' => $me->getMember(),
            'experience_id' => $me->getExperience()?->getId(),
            'experience' => $me->getExperience(),
            'audi_action' => $me->getAudiAction(),
            'audi_date' => $me->getAudiDate()?->format('Y-m-d H:i:s'),
            'audi_user' => $me->getAudiUser(),
        ]);
    }

    #[Route('/member/{memberId}', name: 'member_experience_by_member', methods: ['GET'])]
    public function showByMember(int $memberId, MemberExperienceRepository $repository): JsonResponse
    {
        $data = $repository->findByMemberId($memberId);

        $response = array_map(function (MemberExperience $me) {
            return [
                'id' => $me->getId(),
                'experience_id' => $me->getExperience()?->getId(),
                'experience' => $me->getExperience()?->getName(),
                'audi_action' => $me->getAudiAction(),
                'audi_date' => $me->getAudiDate()?->format('Y-m-d H:i:s'),
                'audi_user' => $me->getAudiUser(),
            ];
        }, $data);

        return $this->json($response);
    }

    #[Route('/', name: 'member_experience_create', methods: ['POST'])]
    public function create(
        Request                    $request,
        EntityManagerInterface     $em,
        MemberRepository           $memberRepo,
        ExperienceRepository       $experienceRepo,
        MemberExperienceRepository $memberExperienceRepo
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $member = $memberRepo->find($data['member_id'] ?? 0);
        $experience = $experienceRepo->find($data['experience_id'] ?? 0);

        if (!$member || !$experience) {
            return $this->json(['error' => 'Miembro o experiencia no encontrada'], 400);
        }

        // Verificar duplicado
        $existing = $memberExperienceRepo->findOneBy([
            'member' => $member,
            'experience' => $experience,
        ]);

        if ($existing) {
            return $this->json([
                'error' => 'Esta experiencia ya está asignada a este miembro.',
                'duplicate' => true,
            ], 409);
        }

        $me = new MemberExperience();
        $me->setMember($member);
        $me->setExperience($experience);
        $me->setAudiAction('I');
        $me->setAudiDate(new DateTime());
        $me->setAudiUser($this->getUser()->getAuditId());

        $em->persist($me);
        $em->flush();

        return $this->json(['success' => true, 'id' => $me->getId()]);
    }

    #[Route('/{id}', name: 'member_experience_update', methods: ['PUT'])]
    public function update(
        int                        $id,
        Request                    $request,
        MemberExperienceRepository $repository,
        EntityManagerInterface     $em,
        MemberRepository           $memberRepo,
        ExperienceRepository       $experienceRepo
    ): JsonResponse
    {
        $me = $repository->find($id);
        if (!$me) {
            return $this->json(['error' => 'Relación no encontrada'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $newMember = $me->getMember();
        $newExperience = $me->getExperience();

        if (isset($data['member_id'])) {
            $member = $memberRepo->find($data['member_id']);
            if ($member) {
                $newMember = $member;
            }
        }

        if (isset($data['experience_id'])) {
            $experience = $experienceRepo->find($data['experience_id']);
            if ($experience) {
                $newExperience = $experience;
            }
        }

        // Verificar duplicado excluyendo el actual
        $existing = $repository->findOneBy([
            'member' => $newMember,
            'experience' => $newExperience,
        ]);

        if ($existing && $existing->getId() !== $me->getId()) {
            return $this->json([
                'error' => 'Esta experiencia ya está asignada a este miembro.',
                'duplicate' => true,
            ], 409);
        }

        $me->setMember($newMember);
        $me->setExperience($newExperience);
        $me->setAudiAction('U');
        $me->setAudiDate(new DateTime());
        $me->setAudiUser($this->getUser()->getAuditId());

        $em->persist($me);
        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'member_experience_delete', methods: ['DELETE'])]
    public function delete(int $id, MemberExperienceRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $me = $repository->find($id);
        if (!$me) {
            return $this->json(['error' => 'Relación no encontrada'], 404);
        }

        $me->setAudiAction('D');
        $me->setAudiDate(new DateTime());
        $me->setAudiUser($this->getUser()->getAuditId());

        $em->flush();

        return $this->json(['success' => true]);
    }
}
