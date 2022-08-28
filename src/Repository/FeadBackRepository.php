<?php

namespace App\Repository;

use App\Entity\FeadBack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FeadBack>
 *
 * @method FeadBack|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeadBack|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeadBack[]    findAll()
 * @method FeadBack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeadBackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeadBack::class);
    }

    public function add(FeadBack $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FeadBack $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return FeadBack[] Returns an array of FeadBack objects
     */
    public function findbyProduct(): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.produit IS NOT NULL ')
            // ->setParameter('val', $value)
            ->orderBy('f.id', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
    /**
     * @return FeadBack[] Returns an array of FeadBack objects
     */
    public function findFeedback(): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.produit IS NULL ')
            // ->setParameter('val', $value)
            ->orderBy('f.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return FeadBack[] Returns an array of FeadBack objects
     */
    public function findFeedProd(): array
    {
        return $this->createQueryBuilder('f')
            ->select('p', 'f')
            ->andWhere('f.produit IS NOT NULL ')
            // ->setParameter('val', $value)
            ->join('f.produit', 'p')
            ->orderBy('f.id', 'DESC')
            // ->groupBy('f.produit')
            ->getQuery()
            ->getResult();
    }


    //    public function findOneBySomeField($value): ?FeadBack
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}