<?php

namespace App\Repository;

use App\Entity\Member;
use App\Entity\MemberInterest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MemberInterestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberInterest::class);
    }

    public function findAllActivos(): array
    {
        return $this->createQueryBuilder('mi')
            ->leftJoin('mi.member', 'm')->addSelect('m')
            ->leftJoin('mi.interest', 'i')->addSelect('i')
            ->andWhere('mi.audiAction IS NULL OR mi.audiAction != :deleted')
            ->setParameter('deleted', 'D')
            ->orderBy('mi.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByMember(Member $member): array
    {
        return $this->createQueryBuilder('mi')
            ->leftJoin('mi.interest', 'i')->addSelect('i')
            ->where('mi.member = :member')
            ->andWhere('mi.audiAction IS NULL OR mi.audiAction != :deleted')
            ->setParameter('member', $member)
            ->setParameter('deleted', 'D')
            ->getQuery()
            ->getResult();
    }

    public function findAllIncluyendoEliminados(): array
    {
        return $this->createQueryBuilder('mi')
            ->leftJoin('mi.member', 'm')->addSelect('m')
            ->leftJoin('mi.interest', 'i')->addSelect('i')
            ->orderBy('mi.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
