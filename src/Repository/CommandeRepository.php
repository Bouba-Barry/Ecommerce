<?php

namespace App\Repository;

use App\Entity\Commande;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function add(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //   /**
    //      * @return Produit[] Returns an array of Produit objects
    //      */
    public function ajout_produit($cmd, $prod, $qte_cmd, $total, $nomProd)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = " INSERT INTO commande_produit(commande_id, produit_id, nom_produit ,qte_cmd, create_at, update_at, total_vente)
        VALUES(:cmd, :prod, :nomProd ,:qte, :ldate, :lupdate ,:total)
        ";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'cmd' => $cmd,
            'prod' => $prod,
            'nomProd' => $nomProd,
            'qte' => $qte_cmd,
            'ldate' => date('y-m-d h:i:s'),
            'lupdate' => date('y-m-d h:i:s'),
            'total' => $total
        ]);

        // returns an array of arrays (i.e. a raw data set)
        // return $resultSet->fetchAllAssociative();
    }


    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function getFacture($cmd)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * FROM commande_produit WHERE commande_id = :cmd";
        $stmt = $conn->prepare($sql);
        $resultSet =   $stmt->executeQuery([
            'cmd' => $cmd,
        ]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


    public function findCmdByAdmin($user)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            // ->andWhere('c. = ')
            ->leftJoin('c.produit', 'p')
            ->join('p.user', 'u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user)
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Commande[]
     */
    public function findCmdStat($stat)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = " SELECT c.* FROM commande c
        Where c.status = :stat and (DATEDIFF(CURRENT_TIMESTAMP(), c.date_cmd)  BETWEEN 0 and 31) ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'stat' => $stat
        ]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function findSaleDays()
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT SUM(f.total_vente) as 'revenu' FROM produit p, commande_produit f
        WHERE p.id = f.produit_id and (DATEDIFF(CURRENT_TIMESTAMP(), f.create_at)  BETWEEN 0 and 1)
        ORDER BY f.create_at DESC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


    public function findNbreOrder()
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "
        SELECT SUM(f.qte_cmd) as 'prod_vente' FROM produit p, commande_produit f
        WHERE p.id = f.produit_id and (DATEDIFF(CURRENT_TIMESTAMP(), f.create_at)  BETWEEN 0 and 31)
        Group by f.produit_id
        ORDER BY f.create_at DESC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
}