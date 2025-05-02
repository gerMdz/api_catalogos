<?php

namespace App\Repository;

use App\Entity\MemberFamily;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MemberFamilyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberFamily::class);
    }

    public function findAllActivos(): array
    {
        return $this->createQueryBuilder('mf')
            ->leftJoin('mf.member', 'm')
            ->leftJoin('mf.relatedMember', 'rm')
            ->leftJoin('mf.family', 'f')
            ->addSelect('m', 'rm', 'f')
            ->andWhere('mf.audiAction IS NULL OR mf.audiAction != :deleted')
            ->setParameter('deleted', 'D')
            ->getQuery()
            ->getResult();
    }

    public function findActivosByMember(int $memberId): array
    {
        return $this->createQueryBuilder('mf')
            ->leftJoin('mf.member', 'm')
            ->leftJoin('mf.relatedMember', 'rm')
            ->leftJoin('mf.family', 'f')
            ->addSelect('m', 'rm', 'f')
            ->andWhere('mf.member = :memberId')
            ->andWhere('mf.audiAction IS NULL OR mf.audiAction != :deleted')
            ->setParameter('memberId', $memberId)
            ->setParameter('deleted', 'D')
            ->getQuery()
            ->getResult();
    }

}
