<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return int|mixed|string
     */
    public function getProductsSortedByDate()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->andWhere('c.isActive = true')
            ->andWhere('p.isActive = true')
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int|mixed|string
     */
    public function getAFewProductsSortedByDate()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->andWhere('c.isActive = true')
            ->andWhere('p.isActive = true')
            ->orderBy('p.date', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult();
    }

    public function getProductsInCategorySortedByDate($value)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->andWhere('c.isActive = true')
            ->where('p.category = ' . $value)
            ->andWhere('p.isActive = true')
            ->orderBy('p.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getProductsByName($value)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.category', 'c')
            ->where('p.title like ' . '\'%' . $value . '%\'')
            ->andWhere('c.isActive = true')
            ->andWhere('p.isActive = true')
            ->getQuery()
            ->getResult();
    }
}
