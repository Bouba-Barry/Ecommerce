<?php

namespace App\Controller;

use doctrine;
use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\Id;
use App\Entity\SousCategorie;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// #[Route('/admin')]
class HomeController extends AbstractController
{

    #[Route('/panier_infos/{id}', name: 'app_panier_infos',methods: ['GET'])]
    public function panier_infos(User $user,PanierRepository $panierRepository,ManagerRegistry $doctrine){

        
        $entityManager = $doctrine->getManager();
        $panier=new Panier();
        
        $panier->setUser($user);
        $entityManager->persist($panier);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        
     
        dd("fh");
        return $this->render('home.html.twig'

        );         
  
    }





    #[Route('/exp', name: 'app_exp',methods: ['GET'])]
    public function exp(ProduitRepository $produitRepository){


        return $this->render('home.html.twig',[
            'produits' => $produitRepository->findAll(),
        ]

        );         
  
    }

    #[Route('/shop_details/{id}', name: 'app_shp_details',methods: ['GET'])]
     public function shop_details($id,ProduitRepository $produitRepository,SousCategorieRepository $sousCategorieRepository){
        // dd($produit);

        $produit=$produitRepository->find($id);


        $produits_similaires=$produitRepository->findBy(['sous_categorie' => $produit->getSousCategorie()]);
      

        $sous_categories=$sousCategorieRepository->findBy(['categorie' => $produit->getSousCategorie()->getCategorie() ]);

      

        $produits_en_relation=$produitRepository->findBy(['sous_categorie' => $sous_categories]);

        // dd($produits_en_relation);
        // dd($produits_similaires);

        return $this->render('frontend/shop-details.html.twig', [
            'produit' => $produit,
            'produits_similaires' => $produits_similaires,
            'produits_en_relation' => $produits_en_relation
        ]);         
  

     }























    #[Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTABLE')  ")]
    #[Route('/admin', name: 'app_home_admin')]
    public function index(UserRepository $userRepository, ProduitRepository $produitRepository): Response
    {

        $clientRecent = $userRepository->findRecentClient();
        $membreRecent = count($clientRecent);

        $clientTotal = $userRepository->findRoles();
        $totalClient = count($clientTotal);

        $pourcentage = ($membreRecent * 100) / $totalClient;

        $produits = $produitRepository->findAll();

        $salesMonth = count($produitRepository->findSalesMonth());
        // $total = 0;
        // foreach ($salesMonth as $s) {
        //     $s->ancien_prix

        // }
        // dd($salesMonth);


        // produit ajouter il y'a au max un mois
        $productRecent = $produitRepository->findRecent();


        return $this->render('home/dashbord.html.twig', [
            'clients' => $clientRecent,
            'produits' => $produits,
            'produitRecents' => $productRecent,
            'totalClient' => $totalClient,
            'RecentClient' => $membreRecent,
            'pourClient' => $pourcentage,
            'month_prod_sales' => $salesMonth,
        ]);
    }


    #[Route('', name: 'app_home')]
    public function home(ProduitRepository $produitRepository): Response
    {
        return $this->render('frontend/home.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/panier/{id}/{slug}/{user_id}', name: 'app_panier', methods: ['GET'])]
    public function panier($id,$slug,$user_id,UserRepository $userRepository,ManagerRegistry $doctrine ,ProduitRepository $produitRepository, PanierRepository $panierRepository): Response
    {

        $entityManager = $doctrine->getManager();
        $panier=new Panier();
        $user=$userRepository->find($user_id);
        $panier->setUser($user);
        $entityManager->persist($panier);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        

        // $val->array_push($produit->getId());
        $d = str_split($id);
        $f=str_split($slug);
         $panier_id=$panier->getId();
        // $array_ids = [];
        for ($i = 0; $i < count($d); $i++) {
            if ($d[$i] !== "," && $f[$i]!==","){
                // $produit=$produitRepository->find($d[$i]);
                // $panier->addProduit($produit);


                $panierRepository->add_to_produit_panier($panier_id,$d[$i],$f[$i]);
               
              


            }
        }
        dd("fin am3lm");       
        
        // if ($this->getUser()) {

        //     $panier->SetUser($this->getUser());
        //     $panier->SetMontant(4);
        //     // $produit = $produitRepository->find($id)
        //     foreach ($array_ids as $id) {

        //         $produit = $produitRepository->find($id);
        //         $panier->addProduit($produit);
        //     }
        //     $panierRepository->add($panier, true);
        // }


        // return $this->render('panier.html.twig', [
        //     'produits' => $produitRepository->findBy(['id' => $array_ids]),
        // ]);
    }
}