<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[]
     */
    public function findActive(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.activo = :active')
            ->setParameter('active', true)
            ->orderBy('c.nombre', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByIdentificador(string $identificador): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.identificador = :slug')
            ->setParameter('slug', $identificador)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
