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
    public function ajout_produit($cmd, $prod, $qte_cmd, $total)
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = " INSERT INTO commande_produit(commande_id, produit_id, qte_cmd, create_at)
        VALUES(:cmd, :prod, :qte, :ldate)
        ";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery([
            'cmd' => $cmd,
            'prod' => $prod,
            'qte' => $qte_cmd,
            'ldate' => new DateTime(),
            // 'total' => $total
        ]);

        // returns an array of arrays (i.e. a raw data set)
        // return $resultSet->fetchAllAssociative();
    }

    //    public function findOneBySomeField($value): ?Commande
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}