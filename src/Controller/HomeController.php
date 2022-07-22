<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Entity\User;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\SousCategorieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

// #[Route('/admin')]
class HomeController extends AbstractController
{


    #[Route('/shop_details/{id}', name: 'app_shp_details', methods: ['GET'])]
    public function shop_details($id, ProduitRepository $produitRepository, SousCategorieRepository $sousCategorieRepository)
    {
        // dd($produit);

        $produit = $produitRepository->find($id);


        $produits_similaires = $produitRepository->findBy(['sous_categorie' => $produit->getSousCategorie()]);


        $sous_categories = $sousCategorieRepository->findBy(['categorie' => $produit->getSousCategorie()->getCategorie()]);



        $produits_en_relation = $produitRepository->findBy(['sous_categorie' => $sous_categories]);

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
        $totalSalesMonth = $produitRepository->TOTALSALESMONTH(); // pour la partie admin
        // dd($totalSalesMonth[0]['total']);
        $MostSalesMonth = $produitRepository->findSalesMonth();
        $NewProduct = $produitRepository->findRecentProduct(); //produit arriver il y'a 2 weeks et maxREsult 10
        // dd($NewProduct);
        $bestSellers = $produitRepository->BestSellers();
        // dd($bestSellers);
        $plusVendus = $produitRepository->MostBuy();
        dd($plusVendus);
        return $this->render('frontend/home.html.twig', [
            'produits' => $produitRepository->findAll(),
            'mostSaleMonth' => $MostSalesMonth,
            'NewProduct' => $NewProduct,
            'bestSellers' => $bestSellers

        ]);
    }

    #[Route('/panier/{id}', name: 'app_panier', methods: ['GET'])]
    public function panier($id, ProduitRepository $produitRepository, PanierRepository $panierRepository): Response
    {

        // $val->array_push($produit->getId());
        $d = str_split($id);
        $array_ids = [];
        for ($i = 0; $i < count($d); $i++) {
            if ($d[$i] !== ",")
                array_push($array_ids, $d[$i]);
        }
        $panier = new Panier();

        if ($this->getUser()) {

            $panier->SetUser($this->getUser());
            $panier->SetMontant(4);
            // $produit = $produitRepository->find($id)
            foreach ($array_ids as $id) {

                $produit = $produitRepository->find($id);
                $panier->addProduit($produit);
            }
            $panierRepository->add($panier, true);
        }


        return $this->render('panier.html.twig', [
            'produits' => $produitRepository->findBy(['id' => $array_ids]),
        ]);
    }
}