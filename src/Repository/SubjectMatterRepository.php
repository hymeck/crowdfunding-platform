<?php

namespace App\Repository;

use App\Entity\SubjectMatter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SubjectMatter|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubjectMatter|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubjectMatter[]    findAll()
 * @method SubjectMatter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubjectMatterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubjectMatter::class);
    }

    // /**
    //  * @return SubjectMatter[] Returns an array of SubjectMatter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubjectMatter
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
