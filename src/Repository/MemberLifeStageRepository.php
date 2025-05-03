<?php

namespace App\Repository;

use App\Entity\MemberLifeStage;
use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MemberLifeStageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberLifeStage::class);
    }

    public function findAllActivos(): array
    {
        return $this->createQueryBuilder('mls')
            ->leftJoin('mls.member', 'm')
            ->leftJoin('mls.lifeStage', 'ls')
            ->addSelect('m', 'ls')
            ->where('mls.audiAction IS NULL OR mls.audiAction != :deleted')
            ->setParameter('deleted', 'D')
            ->getQuery()
            ->getResult();
    }

    public function findByMember(Member $member): array
    {
        return $this->createQueryBuilder('mls')
            ->leftJoin('mls.lifeStage', 'ls')
            ->addSelect('ls')
            ->where('mls.member = :member')
            ->where('mls.audiAction IS NULL OR mls.audiAction != :deleted')
            ->setParameter('member', $member)
            ->setParameter('deleted', 'D')
            ->getQuery()
            ->getResult();
    }

    public function findAllIncluyendoEliminados(): array
    {
        return $this->createQueryBuilder('mls')
            ->leftJoin('mls.member', 'm')
            ->leftJoin('mls.lifeStage', 'ls')
            ->addSelect('m', 'ls')
            ->getQuery()
            ->getResult();
    }
}
