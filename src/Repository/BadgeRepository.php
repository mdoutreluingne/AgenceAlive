<?php

namespace App\Repository;

use App\Entity\Badge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Badge|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badge|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badge[]    findAll()
 * @method Badge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Badge::class);
    }

    /**
     *
     * @param integer $user_id
     * @param string $action
     * @param integer $action_count
     * @return Badge
     */
    public function findWithUnlockForAction(int $user_id, string $action, int $action_count): Badge
    {
        return $this->createQueryBuilder('b')
            ->where('b.action_name = :action_name')
            ->andWhere('b.action_count = :action_count')
            ->andWhere('u.user = :user_id OR u.user iS NULL')
            ->leftJoin('b.unlocks', 'u', 'WITH', 'u.user = :user_id')
            ->select('b, u')
            ->setParameter('action_count', $action_count)
            ->setParameter('action_name', $action)
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Find all badges unlocked by a specific user
     *
     * @param integer $user_id
     * @return array
     */
    public function findUnlockFor(int $user_id): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.unlocks', 'u')
            ->Where('u.user = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Badge[] Returns an array of Badge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Badge
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
