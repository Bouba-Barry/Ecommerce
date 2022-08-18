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


    public function get_produit_reduction()
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ("SELECT * FROM produit_reduction  ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $d = $resultSet->fetchAllAssociative();

        return json_encode($d);
    }





    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findBySearch($attr): array
    {
        // SELECT p FROM DoctrineExtensions\Query\BlogPost p WHERE DATEDIFF(CURRENT_TIME(), p.created) < 7 
        return $this->createQueryBuilder('p')
            //    ->andWhere('p.createAt = :val')
            ->andWhere('p.designation like  :val')
            ->setParameter('val', '%' . $attr . '%')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }


    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findMostPopulareInSearch($json)
    {
        $conn = $this->getEntityManager()->getConnection();
        // WHERE p.id = c.id and JSON_CONTAINS(`$json`, p.id)
        $sql = "
            SELECT p.* FROM produit p, commande_produit c 
            WHERE p.id IN (" . implode(',', $json) . ") and p.id = c.produit_id   
            GROUP BY c.produit_id
            ORDER BY COUNT(*) DESC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function findByUser($user)
    {
        $conn = $this->getEntityManager()->getConnection();
        // WHERE p.id = c.id and JSON_CONTAINS(`$json`, p.id)
        $sql = "
            SELECT p.* FROM produit p, produit_variation v
            where p.id=v.produit_id            
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $result = $resultSet->fetchAllAssociative();
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.user =:user ')
            ->andWhere('p in (:result)')
            ->setParameter('user', $user)
            ->setParameter(':result', $result);

        return $queryBuilder;
    }

    public function findcorbeille()
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where("p.deletedAt is not NULL");

        return $queryBuilder->getQuery()->getResult();
    }

    public function deletefromtrash($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        DELETE FROM produit 
        WHERE  id=:id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return true;
    }


    public function findProduitBy($id)
    {
        // $conn = $this->getEntityManager()->getConnection();
        // // WHERE p.id = c.id and JSON_CONTAINS(`$json`, p.id)
        // $sql = "
        //     SELECT p.* FROM produit p, produit_variation v
        //     where p.id=v.produit_id            
        // ";
        // $stmt = $conn->prepare($sql);
        // $resultSet = $stmt->executeQuery();
        // $result=$resultSet->fetchAllAssociative();
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id =:id ')
            ->setParameter('id', $id);


        return $queryBuilder;
    }


    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    public function findPopularCategory()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT s.categorie_id FROM produit p, commande_produit f, sous_categorie s
        WHERE p.id = f.produit_id and p.sous_categorie_id = s.id 
        GROUP BY s.id
        ORDER BY COUNT(*) DESC
        LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    // /**
    //  * @return array Returns an array of Produit objects
    //  */
    public function findPopularSousCategory()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT s.* FROM produit p, commande_produit f, sous_categorie s
        WHERE p.id = f.produit_id and p.sous_categorie_id = s.id 
        GROUP BY s.id
        ORDER BY COUNT(*) DESC
        LIMIT 3
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findRecentProduct()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT * FROM produit p
        WHERE DATEDIFF(CURRENT_TIMESTAMP(), p.create_at)  BETWEEN 0 and 31
        ORDER BY p.create_at DESC
        LIMIT 12
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        $result = $resultSet->fetchAllAssociative();
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id in (:result) ')
            ->setParameter(':result', $result);

        return $queryBuilder->getQuery()->getResult();
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
    public function PopularProducts()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT p.* FROM produit p, commande_produit f
        WHERE p.id = f.produit_id 
        GROUP BY f.produit_id
        HAVING COUNT(*) > 1
        ORDER BY p.id DESC
        ";

        // et si on prenait les 20 premiers produits les plus vendus A voir après
        $sql2 = "
        SELECT p.* FROM produit p, commande_produit f
        WHERE p.id = f.produit_id 
        GROUP BY f.produit_id
        ORDER BY COUNT(*) DESC
        LIMIT 12
        ";
        $stmt = $conn->prepare($sql2);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        $result = $resultSet->fetchAllAssociative();
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id in (:result) ')
            ->setParameter(':result', $result);

        return $queryBuilder->getQuery()->getResult();
    }



    public function PopularProducts_This_Month()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql2 = "
        SELECT p.* FROM produit p, commande_produit f
        WHERE p.id = f.produit_id and (DATEDIFF(CURRENT_TIMESTAMP(), f.create_at)  BETWEEN 1 and 31)
        GROUP BY f.produit_id
        ORDER BY COUNT(*) DESC
        LIMIT 8
        ";
        $stmt = $conn->prepare($sql2);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        $result = $resultSet->fetchAllAssociative();
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id in (:result) ')
            ->setParameter(':result', $result);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return Produit Returns an array of Produit objects
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

        $result = $resultSet->fetchAllAssociative();
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id in (:result) ')
            ->setParameter(':result', $result);

        return $queryBuilder->getQuery()->getResult();
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    public function BestSellers()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT p.* FROM produit p, commande_produit f
        WHERE p.id = f.produit_id 
        GROUP BY f.produit_id
        ORDER BY SUM(f.qte_cmd) DESC
        LIMIT 20
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        $result = $resultSet->fetchAllAssociative();
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.id in (:result) ')
            ->setParameter(':result', $result);

        return $queryBuilder->getQuery()->getResult();
    }


    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function price_desc()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = " SELECT * FROM produit order by IFNULL(nouveau_prix, 0) desc, ancien_prix desc ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function price_asc()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = " SELECT * FROM produit order by IFNULL(nouveau_prix, 0) asc, ancien_prix asc";
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

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function find_recent_inSearch($json)
    {
        $conn = $this->getEntityManager()->getConnection();
        // WHERE p.id = c.id and JSON_CONTAINS(`$json`, p.id)
        $sql = "
            SELECT p.* FROM produit p
            WHERE p.id IN (" . implode(',', $json) . ") and DATEDIFF(CURRENT_TIMESTAMP(), p.create_at)  BETWEEN 0 and 15
            ORDER BY p.create_at DESC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function find_price_asc_inSearch($json)
    {
        $conn = $this->getEntityManager()->getConnection();
        // WHERE p.id = c.id and JSON_CONTAINS(`$json`, p.id)
        $sql = "
            SELECT p.* FROM produit p 
            WHERE p.id IN (" . implode(',', $json) . ") 
            order by IFNULL(nouveau_prix, 0) asc, ancien_prix asc
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function find_price_desc_inSearch($json)
    {
        $conn = $this->getEntityManager()->getConnection();
        // WHERE p.id = c.id and JSON_CONTAINS(`$json`, p.id)
        $sql = "
            SELECT p.* FROM produit p 
            WHERE p.id IN (" . implode(',', $json) . ") 
            order by IFNULL(nouveau_prix, 0) desc, ancien_prix desc
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


    public function UpdateProduit($prod, $qte)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
        UPDATE produit SET qte_stock = qte_stock - $qte WHERE id = $prod
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return true;
    }

    /**
     * @return Produit[] Returns an array of Categorie objects
     */
    public function findProductsByCategory($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
    SELECT p.* From produit p, categorie c, sous_categorie s
    where c.id = :id and c.id = s.categorie_id and s.id = p.sous_categorie_id
    ORDER By p.create_at DESC
    ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'id' => $id
        ]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
}