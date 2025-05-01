<?php

namespace App\Controller;

use App\Entity\Member;
use App\Repository\CivilStateRepository;
use App\Repository\GenderRepository;
use App\Repository\MemberRepository;
use App\Repository\UsuarioPanelRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/members', name: 'api_members_')]
class MemberController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(
        MemberRepository $memberRepository,
        GenderRepository $genderRepository,
        CivilStateRepository $civilStateRepository
    ): JsonResponse {
        $members = $memberRepository->findBy([], ['lastname' => 'ASC']);
        $generos = $genderRepository->findAll();
        $estadosCiviles = $civilStateRepository->findAll();

        // Armamos mapas para lookup rápido
        $mapGenero = [];
        foreach ($generos as $g) {
            $mapGenero[$g->getId()] = $g->getName();
        }

        $mapEstadoCivil = [];
        foreach ($estadosCiviles as $e) {
            $mapEstadoCivil[$e->getId()] = $e->getName();
        }

        // Recorremos y armamos el array con valores resueltos
        $data = [];
        foreach ($members as $member) {
            $data[] = [
                'id' => $member->getId(),
                'name' => $member->getName(),
                'lastname' => $member->getLastname(),
                'birthdate' => $member->getBirthdate()?->format('Y-m-d'),
                'dniDocument' => $member->getDniDocument(),
                'email' => $member->getEmail(),
                'phone' => $member->getPhone(),
                'address' => $member->getAddress(),
                'genderId' => $member->getGender(),
                'gender' => $mapGenero[$member->getGender()] ?? null,
                'civilStateId' => $member->getCivilState(),
                'civilState' => $mapEstadoCivil[$member->getCivilState()] ?? null,
                'audiAction' => $member->getAudiAction(),
            ];
        }

        return $this->json($data);
    }


    /**
     * @throws Exception
     */
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $member = new Member();
        $member->setName($data['name'] ?? null);
        $member->setLastname($data['lastname'] ?? null);
        $member->setBirthdate(new DateTime($data['birthdate']));
        $member->setDniDocument($data['dni_document'] ?? '');
        $member->setAddress($data['address'] ?? '');
        $member->setEmail($data['email'] ?? null);
        $member->setPhone($data['phone'] ?? null);
        $member->setGender($data['gender_id'] ?? 0);
        $member->setCivilState($data['civil_state_id'] ?? 0);
        $member->setPathPhoto($data['path_photo'] ?? null);
        $member->setNameProfession($data['name_profession'] ?? null);
        $member->setArtisticSkills($data['artistic_skills'] ?? null);
        $member->setCountryId($data['country_id'] ?? null);
        $member->setStateId($data['state_id'] ?? null);
        $member->setDistrictId($data['district_id'] ?? null);
        $member->setLocalitiesId($data['localities_id'] ?? null);
        $member->setBossFamily($data['boss_family'] ?? false);
        $member->setQuantitySons($data['quantity_sons'] ?? null);
        $member->setCelebracion($data['celebracion'] ?? null);
        $member->setNameGuia($data['name_guia'] ?? null);
        $member->setNameGroup($data['name_group'] ?? null);
        $member->setGrupo($data['grupo'] ?? null);
        $member->setParticipateGp($data['participate_gp'] ?? null);

        $member->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $member->setAudiDate(new DateTime());
        $member->setAudiAction('I');

        $em->persist($member);
        $em->flush();

        return $this->json($member);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, Member $member, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $member->setName($data['name'] ?? $member->getName());
        $member->setLastname($data['lastname'] ?? $member->getLastname());
        $member->setBirthdate(isset($data['birthdate']) ? new DateTime($data['birthdate']) : $member->getBirthdate());
        $member->setDniDocument($data['dni_document'] ?? $member->getDniDocument());
        $member->setAddress($data['address'] ?? $member->getAddress());
        $member->setEmail($data['email'] ?? $member->getEmail());
        $member->setPhone($data['phone'] ?? $member->getPhone());
        $member->setGender($data['gender_id'] ?? $member->getGender());
        $member->setCivilState($data['civil_state_id'] ?? $member->getCivilState());
        $member->setPathPhoto($data['path_photo'] ?? $member->getPathPhoto());
        $member->setNameProfession($data['name_profession'] ?? $member->getNameProfession());
        $member->setArtisticSkills($data['artistic_skills'] ?? $member->getArtisticSkills());
        $member->setCountryId($data['country_id'] ?? $member->getCountryId());
        $member->setStateId($data['state_id'] ?? $member->getStateId());
        $member->setDistrictId($data['district_id'] ?? $member->getDistrictId());
        $member->setLocalitiesId($data['localities_id'] ?? $member->getLocalitiesId());
        $member->setBossFamily($data['boss_family'] ?? $member->isBossFamily());
        $member->setQuantitySons($data['quantity_sons'] ?? $member->getQuantitySons());
        $member->setCelebracion($data['celebracion'] ?? $member->getCelebracion());
        $member->setNameGuia($data['name_guia'] ?? $member->getNameGuia());
        $member->setNameGroup($data['name_group'] ?? $member->getNameGroup());
        $member->setGrupo($data['grupo'] ?? $member->getGrupo());
        $member->setParticipateGp($data['participate_gp'] ?? $member->getParticipateGp());

        $member->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $member->setAudiDate(new DateTime());
        $member->setAudiAction('U');

        $em->flush();

        return $this->json($member);
    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, MemberRepository $memberRepository): JsonResponse
    {
        $member = $memberRepository->find($id);

        if (!$member) {
            return new JsonResponse(['error' => 'Miembro no encontrado.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $member->setAudiUser($this->getUser()?->getAuditId() ?? null);
        $member->setAudiDate(new DateTimeImmutable($data['audi_date'] ?? 'now'));
        $member->setAudiAction('D');

        $em->flush();

        return new JsonResponse(['message' => 'Miembro marcado como eliminado.']);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Member           $member, UsuarioPanelRepository $usuarioPanelRepository,
                         GenderRepository $genderRepository, CivilStateRepository $civilStateRepository
    ): JsonResponse
    {
        static $usuariosCache = [];

        $auditUser = null;
        $idAudit = $member->getAudiUser();

        if ($idAudit) {
            if (!isset($usuariosCache[$idAudit])) {
                $userAction = $usuarioPanelRepository->findOneBy(['auditId' => $idAudit]);
                $usuariosCache[$idAudit] = $userAction;
            } else {
                $userAction = $usuariosCache[$idAudit];
            }

            if ($userAction) {
                $auditUser = [
                    'nombre' => $userAction->getNombre(),
                    'email' => $userAction->getEmail()
                ];
            }
        }

        $gender = $genderRepository->find($member->getGender())?->getName();
        $civilState = $civilStateRepository->find($member->getCivilState())?->getName();

        return $this->json([
            'id' => $member->getId(),
            'name' => $member->getName(),
            'lastname' => $member->getLastname(),
            'birthdate' => $member->getBirthdate()?->format('Y-m-d'),
            'dni_document' => $member->getDniDocument(),
            'address' => $member->getAddress(),
            'email' => $member->getEmail(),
            'phone' => $member->getPhone(),
            'gender' => $gender,
            'civilState' => $civilState,
            'audi_user' => $auditUser,
            'audi_date' => $member->getAudiDate()?->format('Y-m-d H:i:s'),
            'audi_action' => $member->getAudiAction(),
        ]);
    }

}
