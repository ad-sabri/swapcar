<?php

namespace App\Repository;

use App\Entity\Gearbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gearbox|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gearbox|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gearbox[]    findAll()
 * @method Gearbox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GearboxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gearbox::class);
    }

    // /**
    //  * @return Gearbox[] Returns an array of Gearbox objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gearbox
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
