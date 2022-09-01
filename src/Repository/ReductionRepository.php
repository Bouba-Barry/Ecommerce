<?php

namespace App\Repository;

use App\Data\FilterData;
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

    public function get_reduction_willfinish()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = ("  SELECT * FROM  reduction where TIMEDIFF(CURRENT_TIMESTAMP(),date_fin)>=0 ");
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        $result = $resultSet->fetchAllAssociative();

        $queryBuilder = $this->createQueryBuilder('v')
            ->where('v.id in (:result) ')
            ->setParameter(':result', $result);
        return $queryBuilder->getQuery()->getResult();
    }

    public function delete_reduction()
    {

        $conn = $this->getEntityManager()->getConnection();
        $sql = ("DELETE from reduction  where TIMEDIFF(CURRENT_TIMESTAMP(),date_fin)>=0  ");
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
        $stmt->executeQuery(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return true;
    }

    /**
     * @return Produit[]
     */
    public function findRedByProducts(FilterData $data, $red)
    {

        $query = $this
            ->createQueryBuilder('r')
            ->leftJoin('r.produits', 'p')
            ->andWhere('r.id = :id ')
            ->setParameter('id', $red)
            ->select('r', 'p')
            ->join('p.sous_categorie', 's')
            ->join('s.categorie', 'c')
            ->leftJoin('p.variation', 'v');

        if (!empty($red)) {
            $query = $query
                ->andWhere('r.id = (:id)')
                ->setParameter('id', $red);
        }
        if (!empty($data->q)) {
            $query = $query
                ->andWhere('p.designation LIKE :q ')
                ->setParameter('q', "%{$data->q}%");
        }
        if (!empty($data->min)) {
            $query = $query
                ->andWhere('p.ancien_prix >= :min')
                ->setParameter('min', $data->min);
        }
        if (!empty($data->max)) {
            $query = $query
                ->andWhere('p.ancien_prix <= :max')
                ->setParameter('max', $data->max);
        };
        if (!empty($data->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $data->categories);
        };
        // if (!empty($data->reductions)) {
        //     $query = $query
        //         ->andWhere('r.id = :reduction')
        //         ->setParameter('reduction', $data->reductions);
        // };
        if (!empty($data->variations)) {
            $query = $query
                ->andWhere('v.id IN (:variation)')
                ->setParameter('variation', $data->variations);
        };
        // ->join('p.sous_categories', 's')
        return $query->getQuery()->getResult();
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