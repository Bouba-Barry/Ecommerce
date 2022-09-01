<?php

namespace App\Repository;

use App\Entity\Reduction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;

/**
 * @extends ServiceEntityRepository<Reduction>
 *
 * @method Reduction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reduction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reduction[]    findAll()
 * @method Reduction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReductionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reduction::class);
    }

    public function add(Reduction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reduction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function get_reduction_willfinish(){
        $conn = $this->getEntityManager()->getConnection();
        $sql=("  SELECT * FROM  reduction where TIMEDIFF(CURRENT_TIMESTAMP(),date_fin)>=0 ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $result=$resultSet->fetchAllAssociative();

        $queryBuilder = $this->createQueryBuilder('v')
            ->where('v.id in (:result) ')
            ->setParameter(':result',$result);
        return $queryBuilder->getQuery()->getResult();
    }

    public function delete_reduction(){

        $conn = $this->getEntityManager()->getConnection();
        $sql=("DELETE from reduction  where TIMEDIFF(CURRENT_TIMESTAMP(),date_fin)>=0  ");
        $stmt = $conn->prepare($sql);
        // from  reduction 
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();

    }



    public function findcorbeille()
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->where("r.deletedAt is not NULL");
            
        return $queryBuilder->getQuery()->getResult();
    }

    public function deletefromtrash($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        DELETE FROM reduction
        WHERE  id=:id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id'=>$id]);

        // returns an array of arrays (i.e. a raw data set)
        return true;
    }

//    /**
//     * @return Reduction[] Returns an array of Reduction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reduction
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
