<?php

namespace App\Repository;

use App\Entity\MemberVoluntary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MemberVoluntaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberVoluntary::class);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('mv')
            ->andWhere('mv.audiAction IS NULL OR mv.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getResult();
    }

    public function findOneActiveById(int $id): ?MemberVoluntary
    {
        return $this->createQueryBuilder('mv')
            ->andWhere('mv.id = :id')
//            ->andWhere('mv.audiAction IS NULL OR mv.audiAction IN (:valid)')
            ->setParameter('id', $id)
//            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByMember(int $memberId): array
    {
        return $this->createQueryBuilder('mv')
            ->leftJoin('mv.member', 'm')
            ->andWhere('m.id = :memberId')
            ->andWhere('mv.audiAction IS NULL OR mv.audiAction IN (:valid)')
            ->setParameter('memberId', $memberId)
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getResult();
    }

    public function findAllIncluyendoEliminados(): array
    {
        return $this->createQueryBuilder('mv')
            ->leftJoin('mv.member', 'm')
            ->leftJoin('mv.socialMedia', 'v')
            ->addSelect('m', 'v')
            ->getQuery()
            ->getResult();
    }

}
