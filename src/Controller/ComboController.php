<?php

namespace App\Controller;

use App\Repository\FamilyRepository;
use App\Repository\InterestRepository;
use App\Repository\LifeStageRepository;
use App\Repository\MemberRepository;
use App\Repository\ExperienceRepository;
use App\Repository\NeedRepository;
use App\Repository\ServiceRepository;
use App\Repository\SocialMediaRepository;
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

    #[Route('/family', name: 'combo_family', methods: ['GET'])]
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

    #[Route('/interest', name: 'combo_interest', methods: ['GET'])]
    public function comboInterest(Request $request, InterestRepository $repo): JsonResponse
    {
        $search = $request->query->get('q');

        $qb = $repo->createQueryBuilder('i')
            ->andWhere('i.audiAction IS NULL OR i.audiAction != :deleted')
            ->setParameter('deleted', 'D')
            ->setMaxResults(10)
            ->orderBy('i.name', 'ASC');

        if ($search) {
            $qb->andWhere('LOWER(i.name) LIKE :search')
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        $results = $qb->getQuery()->getResult();

        return $this->json(array_map(fn($i) => [
            'id' => $i->getId(),
            'label' => (string)$i,
        ], $results));
    }

    #[Route('/life-stage', name: 'combo_life_stage', methods: ['GET'])]
    public function comboLifeStage(Request $request, LifeStageRepository $repo): JsonResponse
    {
        $search = $request->query->get('q');

        $qb = $repo->createQueryBuilder('ls')
            ->andWhere('ls.audiAction IS NULL OR ls.audiAction != :deleted')
            ->setParameter('deleted', 'D')
            ->setMaxResults(30)
            ->orderBy('ls.name', 'ASC');

        if ($search) {
            $qb->andWhere('LOWER(ls.name) LIKE :search')
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        $results = $qb->getQuery()->getResult();

        return $this->json(array_map(fn($ls) => [
            'id' => $ls->getId(),
            'label' => (string)$ls,
        ], $results));
    }

    #[Route('/need', name: 'combo_need', methods: ['GET'])]
    public function comboNeed(Request $request, NeedRepository $repo): JsonResponse
    {
        $search = $request->query->get('q');
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $qb = $repo->createQueryBuilder('n')
            ->andWhere('n.audiAction IS NULL OR n.audiAction != :deleted')
            ->setParameter('deleted', 'D')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('n.name', 'ASC');

        if ($search) {
            $qb->andWhere('LOWER(n.name) LIKE :search')
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        $results = $qb->getQuery()->getResult();

        return $this->json(array_map(fn($n) => [
            'id' => $n->getId(),
            'label' => (string)$n,
        ], $results));
    }

    #[Route('/service', name: 'combo_service', methods: ['GET'])]
    public function comboService(Request $request, ServiceRepository $repo): JsonResponse
    {
        $search = $request->query->get('q');
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $qb = $repo->createQueryBuilder('s')
            ->andWhere('s.audiAction IS NULL OR s.audiAction != :deleted')
            ->setParameter('deleted', 'D')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('s.name', 'ASC');

        if ($search) {
            $qb->andWhere('LOWER(s.name) LIKE :search')
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        $results = $qb->getQuery()->getResult();

        return $this->json(array_map(fn($s) => [
            'id' => $s->getId(),
            'label' => (string)$s,
        ], $results));
    }

    #[Route('/social-media', name: 'combo_social_media', methods: ['GET'])]
    public function comboSocialMedia(Request $request, SocialMediaRepository $repo): JsonResponse
    {
        $search = $request->query->get('q');
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $qb = $repo->createQueryBuilder('s')
            ->andWhere('sm.audiAction IS NULL OR sm.audiAction != :deleted')
            ->setParameter('deleted', 'D')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('sm.name', 'ASC');

        if ($search) {
            $qb->andWhere('LOWER(sm.name) LIKE :search')
                ->setParameter('search', '%' . mb_strtolower($search) . '%');
        }

        $results = $qb->getQuery()->getResult();

        return $this->json(array_map(fn($sm) => [
            'id' => $sm->getId(),
            'label' => (string)$sm,
        ], $results));
    }


}
