<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Get categories
     */
    public function getCategories()
    {
        return $this->createQueryBuilder('p')
            ->select('c.id, c.name')
            ->addSelect('COUNT(c) as total')
            ->leftJoin('p.category', 'c')
            ->groupBy('p.category')
            ->getQuery()
            ->useQueryCache(true)    // here
            ->useResultCache(true)
            ->getResult();
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.something = :value')->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
