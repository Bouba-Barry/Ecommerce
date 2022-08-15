<?php

namespace App\Repository;

use App\Entity\Variation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Variation>
 *
 * @method Variation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Variation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Variation[]    findAll()
 * @method Variation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Variation::class);
    }

    public function add(Variation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }




    public function findVariations($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        // // WHERE p.id = c.id and JSON_CONTAINS(`$json`, p.id)
        $sql = "
            SELECT v.variation_id FROM produit_variation v
            where v.produit_id=:id            
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);
        $result=$resultSet->fetchAllAssociative();
        $queryBuilder = $this->createQueryBuilder('v')
            ->where('v.id in (:result) ')
            ->setParameter(':result',$result);
       
        return $queryBuilder;
    }


    public function findcorbeille()
    {
        $queryBuilder = $this->createQueryBuilder('v')
            ->where("v.deletedAt is not NULL");
            
        return $queryBuilder->getQuery()->getResult();
    }

    public function deletefromtrash($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        DELETE FROM variation
        WHERE  id=:id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id'=>$id]);

        // returns an array of arrays (i.e. a raw data set)
        return true;
    }






    public function remove(Variation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Variation[] Returns an array of Variation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Variation
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
