<?php

namespace App\Controller;

use App\Repository\MemberRepository;
use App\Service\FotoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FotoController extends AbstractController
{
    #[Route('/api/foto/{id}', name: 'api_foto', methods: ['GET'])]
    public function obtenerFoto(int         $id, MemberRepository $memberRepository,
                                FotoService $fotoService): BinaryFileResponse|Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $member = $memberRepository->find($id);

        if (!$member || !$member->getPathPhoto()) {
            return new Response('No hay foto', 404);
        }
        $fotoPath = $fotoService->obtenerOptimizada($member->getPathPhoto());

        if (!$fotoPath) {
            return new Response('No disponible', 404);
        }

        return new BinaryFileResponse($fotoPath);
    }
}
