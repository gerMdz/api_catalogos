<?php

namespace App\Repository;

use App\Entity\MemberSocialMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MemberSocialMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberSocialMedia::class);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('msm')
            ->andWhere('msm.audiAction IS NULL OR msm.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getResult();
    }

    public function findOneActiveById(int $id): ?MemberSocialMedia
    {
        return $this->createQueryBuilder('msm')
            ->andWhere('msm.id = :id')
//            ->andWhere('msm.audiAction IS NULL OR msm.audiAction IN (:valid)')
            ->setParameter('id', $id)
//            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByMember(int $memberId): array
    {
        return $this->createQueryBuilder('msm')
            ->leftJoin('msm.member', 'm')
            ->andWhere('m.id = :memberId')
            ->andWhere('msm.audiAction IS NULL OR msm.audiAction IN (:valid)')
            ->setParameter('memberId', $memberId)
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getResult();
    }

    public function findAllIncluyendoEliminados(): array
    {
        return $this->createQueryBuilder('msm')
            ->leftJoin('msm.member', 'm')
            ->leftJoin('msm.socialMedia', 'sm')
            ->addSelect('m', 'sm')
            ->getQuery()
            ->getResult();
    }

}
