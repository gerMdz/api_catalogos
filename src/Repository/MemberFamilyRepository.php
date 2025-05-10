<?php

namespace App\Repository;

use App\Entity\Member;
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

    public function findUniqueMembers(): array
    {
        return $this->createQueryBuilder('mf')
            ->select('DISTINCT m')
            ->join('mf.member', 'm')
            ->andWhere('mf.audiAction IS NULL OR mf.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->groupBy('m.id')
            ->getQuery()
            ->getResult();
    }

    public function findUniqueMemberIds(): array
    {
        return $this->createQueryBuilder('mf')
            ->select('DISTINCT IDENTITY(mf.member) as member_id')
            ->andWhere('mf.audiAction IS NULL OR mf.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getSingleColumnResult(); // requiere Doctrine >= 2.10
    }

    public function countUniqueMembers(): int
    {
        return (int) $this->createQueryBuilder('mf')
            ->select('COUNT(DISTINCT m.id)')
            ->join('mf.member', 'm')
            ->andWhere('mf.audiAction IS NULL OR mf.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findUniqueMembersForCombo(): array
    {
        return $this->createQueryBuilder('mf')
            ->select('m.id AS id, CONCAT(m.lastname, \', \', m.name, \' - \', m.dniDocument) AS label')
            ->join('mf.member', 'm')
            ->andWhere('mf.audiAction IS NULL OR mf.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->groupBy('m.id')
            ->getQuery()
            ->getArrayResult();
    }

    public function countMembersWithCoexistence(): int
    {
        return (int) $this->createQueryBuilder('mf')
            ->select('COUNT(DISTINCT m.id)')
            ->join('mf.member', 'm')
            ->andWhere('(mf.audiAction IS NULL OR mf.audiAction IN (:valid))')
            ->andWhere('mf.coexists = :yes')
            ->setParameter('valid', ['I', 'U'])
            ->setParameter('yes', 'SI')
            ->getQuery()
            ->getSingleScalarResult();
    }



    public function findMembersWithCoexistence(): array
    {
        return $this->createQueryBuilder('mf')
            ->select('DISTINCT m')
            ->join('mf.member', 'm')
            ->andWhere('(mf.audiAction IS NULL OR mf.audiAction IN (:valid))')
            ->andWhere('mf.coexists = :yes')
            ->setParameter('valid', ['I', 'U'])
            ->setParameter('yes', 'SI')
            ->getQuery()
            ->getResult(); // devuelve entidades Member
    }


    public function countCompleteSurveys(): int
    {
        return (int) $this->createQueryBuilder('mf')
            ->select('COUNT(DISTINCT m.id)')
            ->join('mf.member', 'm')
            ->andWhere('mf.relatedMember IS NOT NULL')
            ->andWhere('(mf.audiAction IS NULL OR mf.audiAction IN (:valid))')
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getSingleScalarResult();
    }






}
