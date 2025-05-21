<?php

namespace App\Controller;

use App\Entity\ApiLog;
use App\Repository\ApiLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/logs')]
class LogController extends AbstractController
{
    #[Route('/', name: 'api_logs_list', methods: ['GET'])]
    public function list(ApiLogRepository $repository): JsonResponse
    {
        $logs = $repository->findBy([], ['loggedAt' => 'DESC'], 100);

        $data = array_map(fn(ApiLog $log) => [
            'id' => $log->getId(),
            'type' => $log->getType(),
            'message' => $log->getMessage(),
            'context' => $log->getContext(),
            'logged_at' => $log->getLoggedAt()->format('Y-m-d H:i:s'),
        ], $logs);

        return $this->json($data);
    }
}