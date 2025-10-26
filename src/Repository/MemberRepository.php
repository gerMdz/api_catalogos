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

    public function countMembersByLifeStage(): array
    {
        return $this->createQueryBuilder('m')
            ->select('ls.id, ls.name, COUNT(DISTINCT m.id) as count')
            ->join('App\Entity\MemberLifeStage', 'mls', 'WITH', 'mls.member = m.id')
            ->join('App\Entity\LifeStage', 'ls', 'WITH', 'mls.lifeStage = ls.id')
            ->where('m.audiAction IS NULL OR m.audiAction IN (:valid)')
            ->andWhere('mls.audiAction IS NULL OR mls.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->groupBy('ls.id')
            ->orderBy('count', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Find members by category filter. If $categoryParam is:
     * - null or empty: returns all members ordered by lastname (no extra filter)
     * - 'null'/'none' (case-insensitive) or 'sin'/'empty'/'nil': members without category assigned
     * - numeric: members with that category id
     */
    public function findByCategory(?string $categoryParam): array
    {
        $qb = $this->createQueryBuilder('m')
            ->orderBy('m.lastname', 'ASC');

        if ($categoryParam === null || $categoryParam === '') {
            return $qb->getQuery()->getResult();
        }

        $normalized = strtolower(trim($categoryParam));
        if (in_array($normalized, ['null', 'none', 'sin', 'empty', 'nil'])) {
            $qb->andWhere('m.category IS NULL');
        } else {
            $qb->andWhere('m.category = :catId')
               ->setParameter('catId', (int)$categoryParam);
        }

        return $qb->getQuery()->getResult();
    }
}