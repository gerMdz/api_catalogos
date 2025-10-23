<?php

namespace App\Repository;

use App\Entity\MembersEnjoys;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MembersEnjoys>
 */
class MembersEnjoysRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembersEnjoys::class);
    }

    /**
     * @return MembersEnjoys[]
     */
    public function findByMemberId(int $memberId): array
    {
        return $this->createQueryBuilder('mej')
            ->andWhere('mej.member = :memberId')
            ->setParameter('memberId', $memberId)
            ->getQuery()
            ->getResult();
    }
}
