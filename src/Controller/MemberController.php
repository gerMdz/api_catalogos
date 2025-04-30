<?php

namespace App\Controller;

use App\Entity\Member;
use App\Repository\MemberRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/members', name: 'api_members_')]
class MemberController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(MemberRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Member $member): JsonResponse
    {
        return $this->json($member);
    }

    /**
     * @throws Exception
     */
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $member = new Member();
        $member->setName($data['name'] ?? '');
        $member->setLastname($data['lastname'] ?? '');
        $member->setBirthdate(new DateTime($data['birthdate']));
        $member->setDniDocument($data['dni_document'] ?? '');
        $member->setAddress($data['address'] ?? '');
        $member->setGenderId($data['gender_id']);
        $member->setCivilStateId($data['civil_state_id']);
        // Y así con el resto...

        $em->persist($member);
        $em->flush();

        return $this->json(['id' => $member->getId()], 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Member $member, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $member->setName($data['name'] ?? $member->getName());
        // Y lo demás igual...

        $em->flush();

        return $this->json(['message' => 'Actualizado']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Member $member, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($member);
        $em->flush();

        return $this->json(['message' => 'Eliminado']);
    }
}
