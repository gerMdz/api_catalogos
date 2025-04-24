<?php

namespace App\Controller;

use App\Service\PersonaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api')]
class PersonaController extends AbstractController
{
    #[Route('/personas', name: 'api_personas', methods: ['GET'])]
    public function index(PersonaService $personaService): JsonResponse
    {
        $personas = $personaService->obtenerPersonas();
        return $this->json($personas);
    }
}
