<?php

namespace App\Repository;

use App\Entity\Quantite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quantite>
 *
 * @method Quantite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quantite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quantite[]    findAll()
 * @method Quantite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quantite::class);
    }

    public function add(Quantite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function findquantite($json)
    {
        $conn = $this->getEntityManager()->getConnection();
        // WHERE p.id = c.id and JSON_CONTAINS(`$json`, p.id)
        $sql = "
            SELECT q.* FROM quantite q
            WHERE q.variations=:json  ";

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery(['json' => $json]);
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function findquantite_produit($json, $id)
    {
        $conn = $this->getEntityManager()->getConnection();
        // WHERE p.id = c.id and JSON_CONTAINS(`$json`, p.id)
        $sql = "
            SELECT q.* FROM quantite q
            WHERE q.variations=:json and q.produit_id=:id ";

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery(['json' => $json, 'id' => $id]);
        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }




    public function remove(Quantite $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function UpdateProduit($id, $prod, $qte)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
        UPDATE quantite SET qte_stock = qte_stock - $qte WHERE id = $id and produit_id = $prod
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return true;
    }

    //    /**
    //     * @return Quantite[] Returns an array of Quantite objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Quantite
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}