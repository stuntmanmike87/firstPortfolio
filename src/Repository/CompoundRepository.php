<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Compound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Compound>
 *
 * @method Compound|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compound|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compound[]    findAll()
 * @method Compound[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class CompoundRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compound::class);
    }

    //    /**
    //     * @return Compound[] Returns an array of Compound objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Compound
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
