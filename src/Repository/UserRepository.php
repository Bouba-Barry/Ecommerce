<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findRoles()
    {
        $conn = $this->getEntityManager()->getConnection();

        // $x = "ROLE_USER";
        // return $this->createQueryBuilder('u')
        //     ->andWhere(' :x in (u.roles) ')
        //     ->setParameter('x', $x)
        //     // ->orderBy('u.id', 'ASC')
        //     //    ->setMaxResults(10)
        //     ->getQuery()
        //     ->getResult();
        $sql = "
        SELECT * FROM user u
        WHERE  JSON_CONTAINS(`roles`, '\"ROLE_USER\"')
        ORDER BY u.id ASC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
    /** 
     * @return User[] Returns an array of User objects
     */
    public function findAdmin()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
       SELECT * FROM user u
       WHERE  JSON_CONTAINS(`roles`, '\"ROLE_SUPER_ADMIN\"')
       ORDER BY u.id ASC
       ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findRecentClient()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
        SELECT * FROM user u
        WHERE  (DATEDIFF(CURRENT_TIMESTAMP(), u.create_at)  BETWEEN 1 and 31)
        ORDER BY u.id ASC
        ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    // public function findOneBySomeField($value): ?User
    // {
    //     return $this->createQueryBuilder('u')
    //         ->andWhere('u.nom = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult();
    // }
}