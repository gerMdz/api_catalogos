<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.audiAction IS NULL OR c.audiAction IN (:valid)')
            ->setParameter('valid', ['I', 'U'])
            ->getQuery()
            ->getResult();
    }

}
