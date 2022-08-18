<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 *
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function add(Categorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllCategories()
    {
        $queryBuilder = $this->createQueryBuilder('c');


        return $queryBuilder;
    }


    public function findcorbeille()
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->where("c.deletedAt is not NULL");

        return $queryBuilder->getQuery()->getResult();
    }

    public function deletefromtrash($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        DELETE FROM categorie 
        WHERE  id=:id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return true;
    }

    public function remove(Categorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    // public function findProductsByCategory($id)
    // {
    //     $conn = $this->getEntityManager()->getConnection();

    //     $sql = "
    // SELECT p.* From produit p, categorie c, sous_categorie s
    // where c.id = :id and c.id = s.categorie_id and s.id = p.sous_categorie_id
    // ORDER By p.create_at DESC
    // ";
    //     $stmt = $conn->prepare($sql);
    //     $resultSet = $stmt->executeQuery([
    //         'id' => $id
    //     ]);

    //     $result = $resultSet->fetchAllAssociative();
    //     $queryBuilder = $this->createQueryBuilder('p')
    //         ->where('p.id in (:result) ')
    //         ->setParameter(':result', $result);

    //     return $queryBuilder->getQuery()->getResult();
    // }



    //    /**
    //     * @return Categorie[] Returns an array of Categorie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Categorie
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}