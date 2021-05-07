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
}
