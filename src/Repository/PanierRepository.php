<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 *
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    public function add_to_produit_panier($panier_id,$produit_id,$qte){

        $conn = $this->getEntityManager()->getConnection();
        $sql="INSERT INTO panier_produit(panier_id,produit_id,qte_produit) VALUES(:panier_id,:produit_id ,:qte)";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id,'produit_id' => $produit_id , 'qte' => $qte ]);

        return true;

    }


    public function edit_produit_panier($panier_id,$produit_id,$qte){

        $conn = $this->getEntityManager()->getConnection();
        $sql="UPDATE panier_produit
         SET qte_produit=:qte
         WHERE panier_id=:panier_id and produit_id=:produit_id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id,'produit_id' => $produit_id , 'qte' => $qte ]);
        // $d=$resultSet->fetchAllAssociative();
        return true;

    }


    public function find_one_produit_panier($panier_id,$produit_id){

        $conn = $this->getEntityManager()->getConnection();
        $sql=("SELECT * FROM panier_produit where panier_id=:panier_id and produit_id=:produit_id ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id , 'produit_id' => $produit_id ]);
        $d=$resultSet->fetchAllAssociative();

        return $d;

    }


    public function delete_one_produit_panier($panier_id,$produit_id){

        $conn = $this->getEntityManager()->getConnection();
        $sql=("DELETE FROM panier_produit where panier_id=:panier_id and produit_id=:produit_id ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id , 'produit_id' => $produit_id ]);
        // $d=$resultSet->fetchAllAssociative();

        return true;

    }





  






    public function find_produit_panier($panier_id){

        $conn = $this->getEntityManager()->getConnection();
        $sql=("SELECT * FROM panier_produit where panier_id=:panier_id ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id ]);
        $d=$resultSet->fetchAllAssociative();

        return json_encode($d);

    }
     
     
   


    public function add(Panier $entity, bool $flush = false): void
    {

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Panier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Panier[] Returns an array of Panier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Panier
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
