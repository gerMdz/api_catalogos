<?php

// src/Repository/MemberExperienceRepository.php
namespace App\Repository;

use App\Entity\MemberExperience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MemberExperience>
 */
class MemberExperienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberExperience::class);
    }

    /**
     * @return MemberExperience[]
     */
    public function findByMemberId(int $memberId): array
    {
        return $this->createQueryBuilder('me')
            ->andWhere('me.member = :memberId')
            ->setParameter('memberId', $memberId)
            ->getQuery()
            ->getResult();
    }

    // Podés agregar más métodos reutilizables si lo necesitás
}
