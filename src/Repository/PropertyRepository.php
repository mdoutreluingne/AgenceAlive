<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\PropertyFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function countForUser(int $user_id): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.users = :user')
            ->setParameter('user', $user_id)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     *
     * @return Query
     */
    public function findAllVisibleQuery(PropertyFilter $search): Query
    {
        $query = $this->findVisibleQuery();

        if ($search->getMaxPrice()) {
           $query = $query
                    ->andWhere('p.price <= :maxprice')
                    ->setParameter('maxprice', $search->getMaxPrice());
        }

        if ($search->getMinSurface()) {
           $query = $query
                    ->andWhere('p.surface >= :minsurface')
                    ->setParameter('minsurface', $search->getMinSurface());
        }

        if ($search->getOptions()->count() > 0) {
            $k = 0;
            foreach ($search->getOptions() as $option) {
                $k++;
                $query = $query
                    ->andWhere(":option$k MEMBER OF p.options")
                    ->setParameter("option$k", $option);
            }
        }
        return $query->getQuery();
    }

    /**
     * Retourne 4 biens
     * @return Property
     */
    public function findLatest(): array
    {
        return $this->findVisibleQuery()
            ->orderBy('p.created', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les biens en fonction de l'id du user
     */
    public function findPropertyByUser($id)
    {
        $sql = "select property.id, property.title from property "
            . "Join user on user.id = property.users_id "
            . "where property.users_id = " . $id . " "
            . "order by property.created DESC";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute(array());
        return $stmt->fetchAll();
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.sold = false');
    }

    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
