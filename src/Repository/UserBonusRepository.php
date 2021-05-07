<?php

namespace App\Repository;

use App\Entity\UserBonus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserBonus|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBonus|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBonus[]    findAll()
 * @method UserBonus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBonusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBonus::class);
    }
}
