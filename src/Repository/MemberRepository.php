<?php

namespace App\Repository;

use App\Entity\Member;
use App\Entity\MemberFamily;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('member')
            ->andWhere('member.audiAction IS NULL OR member.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getResult();
    }

    public function countAllActive(): int
    {
        return (int)$this->createQueryBuilder('member')
            ->select('COUNT(member.id)')
            ->andWhere('member.audiAction IS NULL OR member.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function findMembersLivingAlone(): array
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $subqb = $em->createQueryBuilder();
        $subqb->select('1')
            ->from(\App\Entity\MemberFamily::class, 'mf2')
            ->where('mf2.member = m1')
            ->andWhere('(mf2.audiAction IS NULL OR mf2.audiAction IN (:valid))')
            ->andWhere('mf2.coexists = :yes');

        return $qb
            ->select('m1')
            ->from(\App\Entity\Member::class, 'm1')
            ->where($qb->expr()->not($qb->expr()->exists($subqb->getDQL())))
            ->setParameter('valid', ['I', 'U'])
            ->setParameter('yes', 'SI')
            ->getQuery()
            ->getResult();
    }

    public function countMembersLivingAlone(): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return (int)$qb
            ->select('COUNT(DISTINCT m1.id)')
            ->from(Member::class, 'm1')
            ->where(
                $qb->expr()->not(
                    $qb->expr()->exists(
                        $this->getEntityManager()->createQueryBuilder()
                            ->select('1')
                            ->from(MemberFamily::class, 'mf2')
                            ->where('mf2.member = m1')
                            ->andWhere('(mf2.audiAction IS NULL OR mf2.audiAction IN (:valid))')
                            ->andWhere('mf2.coexists = :yes')
                            ->getDQL()
                    )
                )
            )
            ->setParameter('valid', ['I', 'U'])
            ->setParameter('yes', 'SI')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function findLatest(int $limit = 10): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.audiAction IS NULL OR m.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->orderBy('m.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }

    public function countActive(): int
    {
        return (int)$this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->andWhere('e.audiAction IS NULL OR e.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function countMembersByCivilState(): array
    {
        return $this->createQueryBuilder('m')
            ->select('cs.id, cs.name, COUNT(m.id) as count')
            ->join('App\Entity\CivilState', 'cs', 'WITH', 'm.civilState = cs.id')
            ->where('m.audiAction IS NULL OR m.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->groupBy('cs.id')
            ->orderBy('count', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }
}
