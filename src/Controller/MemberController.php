<?php

namespace App\Controller;

use App\Entity\CivilState;
use App\Entity\Country;
use App\Entity\Districts;
use App\Entity\Gender;
use App\Entity\Locality;
use App\Entity\Member;
use App\Entity\State;
use App\Entity\UsuarioPanel;
use App\Repository\CivilStateRepository;
use App\Repository\GenderRepository;
use App\Repository\MemberRepository;
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

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(
        MemberRepository     $memberRepository,
        GenderRepository     $genderRepository,
        CivilStateRepository $civilStateRepository
    ): JsonResponse
    {
        $members = $memberRepository->findBy([], ['lastname' => 'ASC']);
        $generos = $genderRepository->findAll();
        $estadosCiviles = $civilStateRepository->findAll();


        // Obtener los IDs de todos los miembros que son relatedMember en MemberFamily
        $relatedMemberIds = array_map(fn($row) => (int)$row['id'],
            $this->em->createQuery("
        SELECT DISTINCT IDENTITY(mf.relatedMember) AS id
        FROM App\Entity\MemberFamily mf
        WHERE mf.audiAction IS NULL OR mf.audiAction != 'D'
    ")->getArrayResult()
        );


        // Recorremos y armamos el array con valores resueltos
        $data = [];
        foreach ($members as $member) {
            list($gender, $civil, $state, $country, $district, $locality) = $this->getNombresAsociados($member);
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
                'gender' => $gender,
                'civilStateId' => $member->getCivilState(),
                'civilState' => $civil,
                'relatedMember' => in_array($member->getId(), $relatedMemberIds),
                'audiAction' => $member->getAudiAction(),
                'nameProfession' => $member->getNameProfession(),
                'artisticSkills' => $member->getArtisticSkills(),
                'countryID' => $member->getCountryId(),
                'country' => $country,
                'stateID' => $member->getStateId(),
                'state' => $state,
                'districtId' => $member->getDistrictId(),
                'district' => $district,
                'localityId' => $member->getLocalitiesId(),
                'locality' => $locality,
                'bossFamily' => $member->isBossFamily(),
                'quantitySons' => $member->getQuantitySons(),
                'celebracion' => $member->getCelebracion(),
                'nameGuia' => $member->getNameGuia(),
                'nameGroup' => $member->getNameGroup(),
                'grupo' => $member->getGrupo(),
                'participateGp' => $member->getParticipateGp(),
                'audiDate' => $member->getAudiDate()?->format('Y-m-d H:i:s'),
                'audiUser' => $this->obtenerUsuarioPorAudiUser($member->getAudiUser()),
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
        $member->setDniDocument($data['dniDocument'] ?? $member->getDniDocument());
        $member->setAddress($data['address'] ?? $member->getAddress());
        $member->setEmail($data['email'] ?? $member->getEmail());
        $member->setPhone($data['phone'] ?? $member->getPhone());
        $member->setGender($data['genderId'] ?? $member->getGender());
        $member->setCivilState($data['civilStateId'] ?? $member->getCivilState());
        $member->setPathPhoto($data['path_photo'] ?? $member->getPathPhoto());
        $member->setNameProfession($data['nameProfession'] ?? $member->getNameProfession());
        $member->setArtisticSkills($data['artisticSkills'] ?? $member->getArtisticSkills());
        $member->setCountryId($data['countryId'] ?? $member->getCountryId());
        $member->setStateId($data['stateId'] ?? $member->getStateId());
        $member->setDistrictId($data['districtId'] ?? $member->getDistrictId());
        $member->setLocalitiesId($data['localitiesId'] ?? $member->getLocalitiesId());
        $member->setBossFamily($data['bossFamily'] ?? $member->isBossFamily());
        $member->setQuantitySons($data['quantitySons'] ?? $member->getQuantitySons());
        $member->setCelebracion($data['celebracion'] ?? $member->getCelebracion());
        $member->setNameGuia($data['nameGuia'] ?? $member->getNameGuia());
        $member->setNameGroup($data['nameGroup'] ?? $member->getNameGroup());
        $member->setGrupo($data['grupo'] ?? $member->getGrupo());
        $member->setParticipateGp($data['participateGp'] ?? $member->getParticipateGp());

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
    public function show(int $id, MemberRepository $memberRepo, EntityManagerInterface $em): JsonResponse
    {
        $member = $memberRepo->find($id);
        if (!$member) {
            return $this->json(['error' => 'Miembro no encontrado'], 404);
        }

        // ¿Es relatedMember?
        $relatedMemberIds = array_map(fn($row) => (int)$row['id'],
            $this->em->createQuery("
            SELECT DISTINCT IDENTITY(mf.relatedMember) AS id
            FROM App\Entity\MemberFamily mf
            WHERE mf.audiAction IS NULL OR mf.audiAction != 'D'
        ")->getArrayResult()
        );

        list($gender, $civil, $state, $country, $district, $locality) = $this->getNombresAsociados($member);


        return $this->json([
            'id' => $member->getId(),
            'name' => $member->getName(),
            'lastname' => $member->getLastname(),
            'birthdate' => $member->getBirthdate()?->format('Y-m-d'),
            'dniDocument' => $member->getDniDocument(),
            'address' => $member->getAddress(),
            'email' => $member->getEmail(),
            'phone' => $member->getPhone(),
            'nameProfession' => $member->getNameProfession(),
            'artisticSkills' => $member->getArtisticSkills(),
            'country' => $country,
            'state' => $state,
            'district' => $district,
            'locality' => $locality,
            'bossFamily' => $member->isBossFamily(),
            'celebracion' => $member->getCelebracion(),
            'nameGuia' => $member->getNameGuia(),
            'nameGroup' => $member->getNameGroup(),
            'grupo' => $member->getGrupo(),
            'participateGp' => $member->getParticipateGp(),
            'audiAction' => $member->getAudiAction(),
            'audiDate' => $member->getAudiDate()?->format('Y-m-d H:i:s'),
            'audiUser' => $this->obtenerUsuarioPorAudiUser($member->getAudiUser()),
            'gender' => $gender,
            'civilState' => $civil,
            'relatedMember' => in_array($member->getId(), $relatedMemberIds),

        ]);
    }

    public function obtenerUsuarioPorAudiUser(?int $id)
    {
        $usuario = null;
        if ($id) {
            $usuario = $this->em->getRepository(UsuarioPanel::class)->findOneBy(['auditId' => $id])->getNombre();
        }
        return $usuario;
    }

    /**
     * @param Member $member
     * @return array
     */
    public function getNombresAsociados(Member $member): array
    {
        $em = $this->em;
        $gender = $member->getGender() ? $em->getRepository(Gender::class)->find($member->getGender())->getName() : 'No indicado';
        $civil = $member->getCivilState() ? $em->getRepository(CivilState::class)->find($member->getCivilState())->getName() : 'No indicado';
        $state = $member->getStateId() ? $em->getRepository(State::class)->find($member->getStateId())->getName() : 'No indicado';
        $country = $member->getCountryId() ? $em->getRepository(Country::class)->find($member->getCountryId())->getName() : 'No indicado';
        /** @var Districts $district */
        $district = $member->getDistrictId() ? $em->getRepository(Districts::class)->find($member->getDistrictId())->getName() : 'No indicado';
        $locality = $member->getLocalitiesId() ? $em->getRepository(Locality::class)->find($member->getLocalitiesId())->getName() : 'No indicado';
        return array($gender, $civil, $state, $country, $district, $locality);
    }


}
