<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function add(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    // public function findRecentProduct(): array
    // {
    //     // SELECT p FROM DoctrineExtensions\Query\BlogPost p WHERE DATEDIFF(CURRENT_TIME(), p.created) < 7 
    //     return $this->createQueryBuilder('p')
    //         //    ->andWhere('p.createAt = :val')
    //         ->andWhere('DATEDIFF(CURRENT_TIMESTAMP(), p.createdAt)  BETWEEN 1 and 31 ')
    //         // ->setParameter('val', $value)
    //         ->orderBy('p.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult();
    // }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findRecentProduct()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT * FROM produit p
        WHERE DATEDIFF(CURRENT_TIMESTAMP(), p.create_at)  BETWEEN 0 and 15
        ORDER BY p.id ASC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findSalesMonth()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT p.* FROM produit p, commande_produit f
        WHERE p.id = f.produit_id and (DATEDIFF(CURRENT_TIMESTAMP(), f.create_at)  BETWEEN 1 and 31) 
        ORDER BY p.id ASC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function TOTALSALESMONTH()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT p.* FROM produit p, commande_produit f
        WHERE p.id = f.produit_id and (DATEDIFF(CURRENT_TIMESTAMP(), f.create_at)  BETWEEN 1 and 31) 
        ORDER BY p.id ASC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function BestSellers()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT p.* FROM produit p, commande_produit f
        WHERE p.id = f.produit_id 
        GROUP BY f.produit_id
        HAVING COUNT(*) > 1
        ORDER BY p.id DESC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function MostBuy()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT p.* FROM produit p, commande_produit f
        WHERE p.id = f.produit_id 
        GROUP BY f.produit_id
        ORDER BY COUNT(*) DESC
        LIMIT 1
    
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function PopularProducts()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT p.* FROM produit p, commande_produit f
        WHERE p.id = f.produit_id 
        GROUP BY f.produit_id
        ORDER BY SUM(f.qte_cmd) DESC
        LIMIT 30
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }



    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}