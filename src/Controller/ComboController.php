<?php

namespace App\Controller;

use App\Repository\FamilyRepository;
use App\Repository\InterestRepository;
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
        $search = $request->query->get('q', '');

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
                'value' => $member->getId(),
                'dni' => $member->getDniDocument(),
            ];
        }, $members);

        return $this->json($result);
    }

    #[Route('/experience', name: 'combo_experience', methods: ['GET'])]
    public function comboExperience(Request $request, ExperienceRepository $experienceRepository): JsonResponse
    {
        $search = $request->query->get('q', '');

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

    #[Route('/combo/family', name: 'combo_family', methods: ['GET'])]
    public function comboFamily(Request $request, FamilyRepository $familyRepository): JsonResponse
    {
        $search = $request->query->get('q', '');


        $qb = $familyRepository->createQueryBuilder('f')
            ->setMaxResults(30);

        if ($search) {
            $qb->where('LOWER(f.name) LIKE :search')
                ->setParameter('search', '%' . strtolower($search) . '%');
        }

        $families = $qb->getQuery()->getResult();

        $result = array_map(function ($family) {
            return [
                'id' => $family->getId(),
                'label' => $family->getName(),
            ];
        }, $families);

        return $this->json($result);
    }

    #[Route('/combo/interest', name: 'combo_interest', methods: ['GET'])]
    public function comboInterest(Request $request, InterestRepository $repo): JsonResponse
    {
        $search = $request->query->get('q');

        $qb = $repo->createQueryBuilder('i')
            ->where('i.audiAction != :deleted')
            ->setParameter('deleted', 'D')
            ->setMaxResults(10)
            ->orderBy('i.nombre', 'ASC');

        if ($search) {
            $qb->andWhere('LOWER(i.nombre) LIKE :search')
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        $results = $qb->getQuery()->getResult();

        return $this->json(array_map(fn($i) => [
            'id' => $i->getId(),
            'label' => (string) $i,
        ], $results));
    }


}
