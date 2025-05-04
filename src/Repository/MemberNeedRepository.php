<?php

namespace App\Repository;

use App\Entity\MemberNeed;
use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MemberNeedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberNeed::class);
    }

    public function findAllActivos(): array
    {
        return $this->createQueryBuilder('mn')
            ->leftJoin('mn.member', 'm')
            ->leftJoin('mn.need', 'ls')
            ->addSelect('m', 'ls')
            ->where('mn.audiAction IS NULL OR mn.audiAction != :deleted')
            ->setParameter('deleted', 'D')
            ->getQuery()
            ->getResult();
    }

    public function findByMember(Member $member): array
    {
        return $this->createQueryBuilder('mn')
            ->leftJoin('mn.need', 'ls')
            ->addSelect('ls')
            ->where('mn.member = :member')
            ->andWhere('mn.audiAction IS NULL OR mn.audiAction != :deleted')
            ->setParameter('member', $member)
            ->setParameter('deleted', 'D')
            ->getQuery()
            ->getResult();
    }

    public function findAllIncluyendoEliminados(): array
    {
        return $this->createQueryBuilder('mn')
            ->leftJoin('mn.member', 'm')
            ->leftJoin('mn.need', 'ls')
            ->addSelect('m', 'ls')
            ->getQuery()
            ->getResult();
    }
}
