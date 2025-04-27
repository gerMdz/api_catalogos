<?php

namespace App\Repository;

use App\Entity\CivilState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CivilState>
 *
 * @method CivilState|null find($id, $lockMode = null, $lockVersion = null)
 * @method CivilState|null findOneBy(array $criteria, array $orderBy = null)
 * @method CivilState[]    findAll()
 * @method CivilState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CivilStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CivilState::class);
    }
}
