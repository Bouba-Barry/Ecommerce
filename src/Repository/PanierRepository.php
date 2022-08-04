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

    public function add_to_produit_panier($panier_id, $produit_id, $qte)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = "INSERT INTO panier_produit(panier_id,produit_id,qte_produit) VALUES(:panier_id,:produit_id ,:qte)";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id, 'produit_id' => $produit_id, 'qte' => $qte]);

        return true;
    }


    public function add_to_produit_panier_variations($panier_id,$produit_id,$qte,$variations){

        $conn = $this->getEntityManager()->getConnection();
        $sql="INSERT INTO panier_produit(panier_id,produit_id,qte_produit,variations) VALUES(:panier_id,:produit_id ,:qte,:variations)";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id,'produit_id' => $produit_id , 'qte' => $qte ,'variations' => $variations ]);

        return true;

    }

 
    public function edit_produit_panier_variations($panier_id,$produit_id,$qte,$variations){

        $conn = $this->getEntityManager()->getConnection();
        $sql="UPDATE panier_produit
         SET qte_produit=:qte
         WHERE panier_id=:panier_id and produit_id=:produit_id and variations=:variations ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id, 'variations' => $variations  ,'produit_id' => $produit_id , 'qte' => $qte ]);
        // $d=$resultSet->fetchAllAssociative();
        return true;

    }




    public function edit_produit_panier($panier_id,$produit_id,$qte){

        $conn = $this->getEntityManager()->getConnection();
        $sql = "UPDATE panier_produit
         SET qte_produit=:qte
         WHERE panier_id=:panier_id and produit_id=:produit_id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id, 'produit_id' => $produit_id, 'qte' => $qte]);
        // $d=$resultSet->fetchAllAssociative();
        return true;
    }

    public function findquantite_panier($json,$panier)
    {
        $conn = $this->getEntityManager()->getConnection();
        // WHERE p.id = c.id and JSON_CONTAINS(`$json`, p.id)
        $sql = "
            SELECT p.* FROM panier_produit p
            WHERE p.variations=:json and p.panier_id=:panier  ";
        
        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery(['json' => $json,'panier' => $panier ]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


    public function find_one_produit_panier($panier_id, $produit_id)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ("SELECT * FROM panier_produit where panier_id=:panier_id and produit_id=:produit_id ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id, 'produit_id' => $produit_id]);
        $d = $resultSet->fetchAllAssociative();

        return $d;
    }


    public function delete_one_produit_panier($panier_id, $produit_id)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ("DELETE FROM panier_produit where panier_id=:panier_id and produit_id=:produit_id ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id, 'produit_id' => $produit_id]);
        // $d=$resultSet->fetchAllAssociative();

        return true;
    }


    public function delete_one_produit_panier_variations($panier_id,$produit_id,$variations){

        $conn = $this->getEntityManager()->getConnection();
        $sql=("DELETE FROM panier_produit where panier_id=:panier_id and produit_id=:produit_id and variations=:variations ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id , 'produit_id' => $produit_id , 'variations' => $variations ]);
        // $d=$resultSet->fetchAllAssociative();

        return true;

    }



    public function check($produit_id, $panier_id)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ("SELECT * FROM panier_produit where produit_id=:produit_id and panier_id=:panier_id ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['produit_id' => $produit_id, 'panier_id' => $panier_id]);
        $d = $resultSet->fetchAllAssociative();

        return json_encode($d);
    }








    public function find_produit_panier($panier_id)
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ("SELECT * FROM panier_produit where panier_id=:panier_id ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier_id]);
        $d = $resultSet->fetchAllAssociative();

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


    public function selectPanierProd($panier, $prod_id)
    {


        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM panier_produit WHERE panier_id = :panier_id and produit_id = :prod_id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier, 'prod_id' => $prod_id]);

        return $resultSet->fetchAllAssociative();
    }

    public function getQteProd($panier, $prod_id)
    {


        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT qte_produit FROM panier_produit WHERE panier_id = :panier_id and produit_id = :prod_id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier, 'prod_id' => $prod_id]);
        return $resultSet->fetchAllAssociative();
    }

    public function RemoveProd($panier, $prod_id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = " DELETE from panier_produit WHERE panier_id = :panier_id and produit_id = :prod_id";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['panier_id' => $panier, 'prod_id' => $prod_id]);
        return true;
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