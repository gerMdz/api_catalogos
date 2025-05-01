<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use App\Repository\ExperienceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/combo')]
class ComboController extends AbstractController
{
    #[Route('/member', name: 'combo_member', methods: ['GET'])]
    public function comboMember(Request $request, MemberRepository $memberRepository): JsonResponse
    {
        $search = $request->query->get('search', '');

        $qb = $memberRepository->createQueryBuilder('m')
            ->setMaxResults(30);

        if ($search) {
            $qb->andWhere('LOWER(m.name) LIKE :search OR LOWER(m.lastname) LIKE :search OR m.dniDocument LIKE :search')
                ->setParameter('search', '%' . strtolower($search) . '%');
        }

        $members = $qb->getQuery()->getResult();

        $result = array_map(function ($member) {
            return [
                'id' => $member->getId(),
                'label' => $member->getLastname() . ' ' . $member->getName() . ' (' . $member->getDniDocument() . ')',
                'dni' => $member->getDniDocument(),
            ];
        }, $members);

        return $this->json($result);
    }

    #[Route('/experience', name: 'combo_experience', methods: ['GET'])]
    public function comboExperience(Request $request, ExperienceRepository $experienceRepository): JsonResponse
    {
        $search = $request->query->get('search', '');

        $qb = $experienceRepository->createQueryBuilder('e')
            ->setMaxResults(10);

        $experiences = $experienceRepository->findBy([], null, 10);
        if ($search) {
            $qb->andWhere('LOWER(e.name) LIKE :search')
                ->setParameter('search', '%' . strtolower($search) . '%');
            $experiences = $qb->getQuery()->getResult();
        }

        $result = array_map(function ($experience) {
            return [
                'id' => $experience->getId(),
                'label' => $experience->getName(),
            ];
        }, $experiences);

        return $this->json($result);
    }
}
