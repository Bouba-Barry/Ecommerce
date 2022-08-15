<?php

namespace App\Repository;

use App\Entity\SousCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SousCategorie>
 *
 * @method SousCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousCategorie[]    findAll()
 * @method SousCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousCategorie::class);
    }

    public function add(SousCategorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function findcorbeille()
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->where("s.deletedAt is not NULL");
            
        return $queryBuilder->getQuery()->getResult();
    }

    public function deletefromtrash($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        DELETE FROM sous_categorie 
        WHERE  id=:id
        ";
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['id'=>$id]);

        // returns an array of arrays (i.e. a raw data set)
        return true;
    }

    public function remove(SousCategorie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Produit[] Returns an array of Produit objects
     */
    public function findProducts($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT p.* FROM produit p, sous_categorie s
        WHERE  s.id = $id and p.sous_categorie_id = s.id
        ORDER BY p.id DESC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
    // /**
    //  * @return Produit[] Returns an array of User objects
    //  */
    // public function findBySome($id)
    // {
    //     // The ResultSetMapping maps the SQL result to entities
    //     $rsm = $this->createResultSetMappingBuilder('s');

    //     $rawQuery = sprintf(
    //         'SELECT %p
    //     FROM produit p, sous_categorie s 
    //     WHERE s.id = $id and p.sous_categorie_id = s.id,
    //         $rsm->generateSelectClause()
    //     );

    //     $query = $this->getEntityManager()->createNativeQuery($rawQuery, $rsm);
    //     // $query->setParameter('role', $role);
    //     $query->setParameter('role', sprintf('"%s"', $role));

    //     return $query->getResult();
    // }
}