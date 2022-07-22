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

        // $u = $user->find(2);
        // $userr = UserRepository->find(2);
        $user = new User();

        // $user->getUserIdentifier();
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
        // $user->setProfile($faker->imageUrl(640,480));
        $client = new User();

        for ($l = 0; $l < 10; $l++) {

            $client->setNom($faker->firstName());
            $client->setPrenom($faker->lastName());
            $client->setEmail($faker->freeEmail());
            $password = $this->hasher->hashPassword($client, 'cli' . $l);
            $client->setPassword($password);

            $client->setRoles(array('ROLE_USER'));
            $client->setAdresse($faker->streetAddress());
            $client->setTelephone($faker->phoneNumber());

            $manager->persist($client);
        }

        $attribut = new Attribut();
        $attribut2 = new Attribut();

        $attribut->setNom('couleur');
        $manager->persist($attribut);
        $attribut2->setNom('Taille');
        $manager->persist($attribut2);

        $variation = new Variation();


        for ($i = 1; $i < 5; $i++) {
            $categorie = new Categorie();
            $categorie->setTitre($faker->sentence());

            $manager->persist($categorie);

            for ($k = 1; $k <= mt_rand(1, 3); $k++) {
                $sous_categorie = new SousCategorie();
                $sous_categorie->setTitre($faker->sentence());
                $sous_categorie->setCategorie($categorie);

                $manager->persist($sous_categorie);

                for ($a = 1; $a <= mt_rand(3, 4); $a++) {

                    $product = new Produit();
                    $product->setDesignation($faker->company());
                    $product->setDescription($faker->sentence());
                    $product->setAncienPrix($faker->randomFloat($nbMaxDecimals = 2, $min = 1000, 4000));
                    $product->setNouveauPrix($faker->randomFloat($nbMaxDecimals = 2, $min = 500, 900));
                    $product->setImageProduit($faker->imageUrl(640, 480));
                    $product->setUser($user);
                    $product->setQteStock($faker->numberBetween(20, 50));
                    $product->setSousCategorie($sous_categorie);

                    // $product->setUser(2);
                    $manager->persist($product);

                    for ($o = 0; $o <= 5; $o++) {
                        $variation->setNom($faker->colorName());
                        $variation->setCode($faker->hexcolor());
                        $variation->setAttribut($attribut);
                        $variation->addProduit($product);

                        $manager->persist($variation);
                    }
                }
            }
        }

        $manager->flush();
    }
}