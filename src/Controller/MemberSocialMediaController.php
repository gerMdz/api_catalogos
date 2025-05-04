<?php

namespace App\Controller;

use App\Entity\MemberSocialMedia;
use App\Repository\MemberSocialMediaRepository;
use App\Entity\Member;
use App\Entity\SocialMedia;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/api/member-social-media')]
class MemberSocialMediaController extends AbstractApiController
{
    #[Route('/', name: 'member_social_media_list', methods: ['GET'])]
    public function list(Request $request, MemberSocialMediaRepository $repo): JsonResponse
    {
        $incluirEliminados = $request->query->getBoolean('all', false);

        $items = $incluirEliminados
            ? $repo->findAllIncluyendoEliminados()
            : $repo->findAllActive();

        return $this->json(array_map(fn(MemberSocialMedia $item) => [
            'id' => $item->getId(),
            'audiUser' => $this->obtenerUsuarioPorAudiUser($item->getAudiUser()),
            'audiDate' => $item->getAudiDate()?->format('Y-m-d H:i:s'),
            'audiAction' => $item->getAudiAction() ?? 'I',
            'member' => [
                'id' => $item->getMember()?->getId(),
                'nombre' => $item->getMember()?->getNombreCompletoConDni(),
            ],
            'socialMedia' => [
                'id' => $item->getSocialMedia()?->getId(),
                'nombre' => $item->getSocialMedia()?->getName(),
                'otras' => $item->getOtherSocialMedia()
            ]
        ], $items));
    }


    #[Route('/{memberId}', name: 'member_social_media_show', methods: ['GET'])]
    public function show(int $memberId, MemberSocialMediaRepository $repo): JsonResponse
    {
        $data = $repo->findByMember($memberId);
        $result = [];

        foreach ($data as $ms) {
            $result[] = [
                'id' => $ms->getId(),
                'member' => $ms->getMember()?->getNombreCompletoConDni(),
                'socialMedia' => $ms->getSocialMedia()?->getName(),
                'otras' => $ms->getOtherSocialMedia()
            ];
        }

        return $this->json($result);
    }

    #[Route('', name: 'member_social_media_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $member = $em->getRepository(Member::class)->find($data['member']);
        $socialMedia = $em->getRepository(SocialMedia::class)->find($data['socialMedia']);

        if (!$member || !$socialMedia) {
            return $this->json(['error' => 'Datos inválidos'], 400);
        }

        $entity = new MemberSocialMedia();
        $entity->setMember($member);
        $entity->setSocialMedia($socialMedia);
        $entity->setAudiUser($this->getUser()?->getAuditId());
        $entity->setAudiDate(new DateTime());
        $entity->setAudiAction('I');

        $em->persist($entity);
        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'member_social_media_update', methods: ['PUT'])]
    public function update(int $id, Request $request, MemberSocialMediaRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $entity = $repo->findOneActiveById($id);
        if (!$entity) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $socialMedia = $em->getRepository(SocialMedia::class)->find($data['socialMedia'] ?? null);
        if ($socialMedia) {
            $entity->setSocialMedia($socialMedia);
        }

        $entity->setAudiUser($this->getUser()?->getAuditId());
        $entity->setAudiDate(new DateTime());
        $entity->setAudiAction('U');

        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', name: 'member_social_media_delete', methods: ['DELETE'])]
    public function delete(int $id, MemberSocialMediaRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $entity = $repo->findOneActiveById($id);
        if (!$entity) {
            return $this->json(['error' => 'Registro no encontrado'], 404);
        }

        $entity->setAudiUser($this->getUser()?->getAuditId());
        $entity->setAudiDate(new DateTime());
        $entity->setAudiAction('D');

        $em->flush();

        return $this->json(['success' => true]);
    }
}
