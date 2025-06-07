<?php

namespace App\Controller;

use App\Repository\MemberFamilyRepository;
use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/dashboard')]
class DashboardController extends AbstractController
{

    #[Route('', name: 'dashboard_index', methods: ['GET'])]
    public function index(
        MemberRepository       $memberRepo,
        MemberFamilyRepository $familyRepo
    ): JsonResponse
    {
        return $this->json([
            'totalMembers' => $memberRepo->countActive(),
            'membersWithCoexistence' => $familyRepo->countMembersWithCoexistence(),
            'membersLivingAlone' => $memberRepo->countMembersLivingAlone(),
            'completeSurveys' => $familyRepo->countCompleteSurveys(),
            'latestMembers' => $memberRepo->findLatest(10),
        ]);
    }

    #[Route('/fichas_totales', name: 'app_dashboard_fichas_totales', methods: ['GET'])]
    public function fichasTotales(MemberRepository $memberRepository): JsonResponse
    {
        return $this->json(['cant' => $memberRepository->countAllActive()]);

    }

    #[Route('/encuestas_realizadas', name: 'app_dashboard_encuestas_realizadas', methods: ['GET'])]
    public function encuestasRealizadas(MemberRepository $memberRepository): JsonResponse
    {
        return $this->json(['cant' => $memberRepository->countAllActive()]);
    }

    #[Route('/miembros_por_estado_civil', name: 'app_dashboard_miembros_por_estado_civil', methods: ['GET'])]
    public function miembrosPorEstadoCivil(MemberRepository $memberRepository): JsonResponse
    {
        return $this->json($memberRepository->countMembersByCivilState());
    }

    #[Route('/miembros_por_etapa_vida', name: 'app_dashboard_miembros_por_etapa_vida', methods: ['GET'])]
    public function miembrosPorEtapaVida(MemberRepository $memberRepository): JsonResponse
    {
        return $this->json($memberRepository->countMembersByLifeStage());
    }
}
