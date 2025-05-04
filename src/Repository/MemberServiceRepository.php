<?php

namespace App\Repository;

use App\Entity\MemberService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MemberServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberService::class);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('ms')
            ->andWhere('ms.audiAction IS NULL OR ms.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getResult();
    }

    public function findOneActiveById(int $id): ?MemberService
    {
        return $this->createQueryBuilder('ms')
            ->andWhere('ms.id = :id')
//            ->andWhere('ms.audiAction IS NULL OR ms.audiAction IN (:valid)')
            ->setParameter('id', $id)
//            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByMember(int $memberId): array
    {
        return $this->createQueryBuilder('ms')
            ->leftJoin('ms.member', 'm')
            ->andWhere('m.id = :memberId')
            ->andWhere('ms.audiAction IS NULL OR ms.audiAction IN (:valid)')
            ->setParameter('memberId', $memberId)
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getResult();
    }

    public function findAllIncluyendoEliminados(): array
    {
        return $this->createQueryBuilder('ms')
            ->leftJoin('ms.member', 'm')
            ->leftJoin('ms.service', 's')
            ->addSelect('m', 's')
            ->getQuery()
            ->getResult();
    }

}
