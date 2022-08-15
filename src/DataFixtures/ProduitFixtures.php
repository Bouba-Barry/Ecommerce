<?php

namespace App\DataFixtures;

use App\Entity\Attribut;
use Faker\Factory;
use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Entity\User;
use App\Entity\Variation;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProduitFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {


        $faker = \Faker\Factory::create('fr_FR');
        $user = new User();

        $user->setNom('Barry');
        $user->setPrenom('Boubacar');
        $user->setEmail('bouba@gmail.com');
        $password = $this->hasher->hashPassword($user, 'barry1234');
        $user->setPassword($password);
        $roles[] = 'ROLE_SUPER_ADMIN';
        $user->setRoles($roles);
        $user->setAdresse('Marrakech');
        $user->setTelephone('0644628285');

        $manager->persist($user);
        $manager->flush();
    }
}