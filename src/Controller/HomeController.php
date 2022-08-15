<?php

namespace App\Controller;

use doctrine;
use App\Entity\User;
use App\Entity\Ville;
use App\Entity\Panier;
use App\Entity\Region;
use App\Entity\Produit;
use App\Entity\Reduction;
use Doctrine\ORM\Mapping\Id;
use App\Entity\SousCategorie;
use App\Repository\AttributRepository;
use App\Repository\FeadBackRepository;
use App\Repository\UserRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\ReductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;


// #[Route('/admin')]
class HomeController extends AbstractController
{


    // #[Route('/quantite_produit_panier/{id}/{slug}', name: 'app_quantite_produit_panier', methods: ['GET'])]
    // public function quantite_produit_panier($id,$slug, UserRepository $userRepository ,ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    // {

    //     $panier = $panierRepository->findOneBy(['user' => $userRepository->find($slug)]);
    //     $produit_panier=$panierRepository->find_one_produit_panier($panier->getId(),$id);
        
          
    //     $json = json_encode($produit_panier);
    //     return $this->json($json);
    // }




    #[Route('/discover_product/{id}', name: 'app_discover_product', methods: ['GET'])]
    public function discover_product(Reduction $reduction, SerializerInterface $serializer, ProduitRepository $produitRepository, PanierRepository $panierRepository)
    {

        $products = $reduction->getProduits();
        // $json = $serializer->serialize($products, 'json', ['groups' => ['prod:read']]);


        return $this->render('frontend/discover_product.html.twig', [
            'produits' => $products
        ]);
    }






    #[Route('/checkout/{id}/{slug}', name: 'app_checkout', methods: ['GET'])]
    public function checkout(User $user,$slug,ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine)
    {
        // dd($slug);
        $panier = $panierRepository->findOneBy(['user' => $user]);
        $panier_produit = $panierRepository->find_produit_panier($panier->getId());
        $vals = [];
        $obj = json_decode($panier_produit);
        $length = 0;
        foreach ($obj as $panier_prod) {
            $length = $length + $panier_prod->qte_produit;
        }
        foreach ($obj as $val) {
            array_push($vals, $val->produit_id);
            // $length=$length+$panier_produit;
        }
        // dd($vals);
        $produits = $produitRepository->findBy(['id' => $vals]);

        
        return $this->render('frontend/checkout.html.twig', [
            'panier_produits' => $obj,
            'produits' => $produits,
            'length' => $length,
            'Montant' => $slug
        ]);
    }

    #[Route('/panier_check/{id}/{id_user}', name: 'app_panier_check', methods: ['GET'])]
    public function check_panier($id, $id_user, UserRepository $userRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {

        $panier = $panierRepository->findOneBy(['user' => $userRepository->findOneBy(['id' => $id_user])]);

        $panier = $panierRepository->check($id, $panier->getId());

        $panier = json_decode($panier);

        if (count($panier) > 0) {
            return $this->json(1);
        } else {
            return $this->json(0);
        }
    }











    #[Route('/getVilles/{id}', name: 'app_getVilles', methods: ['GET'])]
    public function getVilles(Region $region, SerializerInterface $serializer, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {

        $region_villes = $region->getVilles();
        $json = $serializer->serialize($region_villes, 'json', ['groups' => ['ville']]);

        $json = json_decode($json);
        return $this->json($json);
    }



    #[Route('/panier_infos/{id}', name: 'app_panier_infos', methods: ['GET'])]
    public function panier_infos(User $user, AttributRepository $attributRepository,ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine)
    {


        // $entityManager = $doctrine->getManager();
        // $panier=new Panier();

        // $panier->setUser($user);
        // $entityManager->persist($panier);

        // // actually executes the queries (i.e. the INSERT query)
        // $entityManager->flush();


        $panier = $panierRepository->findOneBy(['user' => $user]);

        $panier_produit = $panierRepository->find_produit_panier($panier->getId());
        $vals = [];
        $obj = json_decode($panier_produit);

        // dd($obj[0]->produit_id);
        //  $val=array_push($obj[i]->produit_id);
        // dd($val);
        $length = 0;
        foreach ($obj as $panier_prod) {
            $length = $length + $panier_prod->qte_produit;
        }

        //  dd($length);
        foreach ($obj as $val) {
            array_push($vals, $val->produit_id);
            // $length=$length+$panier_produit;
        }
        // dd($vals);
        $produits = $produitRepository->findBy(['id' => $vals]);
        // $obj1=json_encode($obj);
        // dd($obj[0]->variations);
       
        // foreach($obj1 as $val){
        //     $val->variations=str_replace("[\"","",$val->variations);
        //     $val->variations=str_replace("\"]","",$val->variations);
        //     $val->variations=str_replace("\"","",$val->variations);
        //     $val->variations=explode(',',$val->variations);
        // }
      





        //  dd($produits);
        return $this->render('frontend/cart.html.twig', [
            'panier_produits' => $obj,
            'produits' => $produits,
            'length' => $length,
            'attributs' => $attributRepository->findAll(),
        ]);
    }


    #[Route('/getProduit/{id}', name: 'app_get_produit', methods: ['GET'])]
    public function getProduit(Produit $produit, SerializerInterface $serializer): JsonResponse
    {

        $json = $serializer->serialize($produit, 'json', ['groups' => ['prod:read']]);
        // dd($this->json($res));
        // dd($t);

        $json = json_decode($json);

        return $this->json($json);
    }





    #[Route('/delete_reduction', name: 'app_delete_reduction', methods: ['GET'])]
    public function delete_reduction(ReductionRepository $reductionRepository)
    {

        $reduction = $reductionRepository->delete_reduction();

        return true;
    }

    // #[Route('/check_reduction', name: 'app_check_reduction', methods: ['GET'])]
    // public function check_reduction(ProduitRepository $produitRepository,SerializerInterface $serializer)
    // {
    //     $json = $serializer->serialize($produitRepository->findAll(), 'json', ['groups' => ['prod:check']]);
          
    //     $json=json_decode($json);
    //     foreach($json as $array){
    //         if(count($array->variation())){
    //            $array->setNouveauPrix(0);
    //         }
    //         else
    //         continue;
    //     }
    //     dd($json);

    // }





    #[Route('/shop_details/{id}', name: 'app_shop_details', methods: ['GET'])]
    public function shop_details($id, FeadBackRepository $feadBackRepository, SerializerInterface $serializer, ProduitRepository $produitRepository, SousCategorieRepository $sousCategorieRepository)
    {
        // dd($produit);

        $produit = $produitRepository->find($id);
        
        // dd($produit);

        $produits_similaires = $produitRepository->findBy(['sous_categorie' => $produit->getSousCategorie()]);


        $sous_categories = $sousCategorieRepository->findBy(['categorie' => $produit->getSousCategorie()->getCategorie()]);

        $produits_en_relation = $produitRepository->findBy(['sous_categorie' => $sous_categories]);

        $popular_products = $produitRepository->PopularProducts();
        $reviews = $feadBackRepository->findbyProduct();
        // dd($reviews);
        // dd($produits_en_relation);
        // dd($produits_similaires);
        // dd($popular_products);
        $json = $serializer->serialize($popular_products, 'json', ['groups' => ['prod:read']]);
        $json = json_decode($json);
        // dd($json);

        return $this->render('frontend/shop-details.html.twig', [
            'produit' => $produit,
            'produits_similaires' => $produits_similaires,
            'produits_en_relation' => $produits_en_relation,
            'popular_products' => $json,
            'reviews' => $reviews
        ]);
    }

    #[ROUTE('/shoplist', name: 'app_home_shop')]
    public function shopList(ProduitRepository $produitRepository, Request $request): Response
    {

        // dd($attr);
        // $v = $request->get('choice');
        // if ($v) {
        //     dd($v);
        // }
        $priceAsc = $produitRepository->price_asc();
        // dd($priceAsc);
        $priceDesc = $produitRepository->price_desc();
        // dd($priceDesc);
        $popular = $produitRepository->PopularProducts();
        // dd($popular);
        $produits_recent = $produitRepository->findRecentProduct();
        $produits = $produitRepository->findAll();

        return $this->render('frontend/shoplist.html.twig', [
            'produits' => $produits
        ]);
    }

    #[ROUTE('/search', name: 'app_search_shop', methods: ['GET', 'POST'])]
    public function shoplist_search(SerializerInterface $serializer, ProduitRepository $produitRepository, Request $request): Response
    {
        $value = $request->get('searchInput');

        $choice = $request->get('choice_val');

        // dd($value);
        $res = $produitRepository->findBySearch($value);
        // dd($res);
        // dd($res);

        $json = $serializer->serialize($res, 'json', ['groups' => ['prod:read']]);
        $json = json_decode($json);
        $tab = array();
        for ($i = 0; $i < count($json); $i++) {
            array_push($tab, $json[$i]->id);
        }
        // dd($tab);

        if ($choice && (!empty($tab))) {
            switch ($choice) {
                case 'default':
                    $ret = [];
                    break;
                case 'populaire':
                    $ret = $produitRepository->findMostPopulareInSearch($tab);
                    break;
                case 'new':
                    $ret = $produitRepository->find_recent_inSearch($tab);
                    break;
                case 'price_asc':
                    $ret = $produitRepository->find_price_asc_inSearch($tab);
                    break;
                case 'price_desc':
                    $ret = $produitRepository->find_price_asc_inSearch($tab);
                    break;
            }
            return $this->json($ret);
        }


        return $this->render('frontend/shoplist_search.html.twig', [
            'produits' => $json,
            'size' => count($json)
        ]);
    }

    #[ROUTE('/shortProduct/{val}', name: 'app_short_by', methods: ['GET'])]
    public function shortBy($val,  SerializerInterface $serializer, ProduitRepository $produitRepository): JsonResponse
    {
        // dd($val);
        // dd($attr);
        // $res = [];
        // dd("je suis bien arriver dans le controller");
        switch ($val) {
            case 'default':
                $res = [];
                break;
            case 'populaire':
                $res = $produitRepository->BestSellers();
                break;
            case 'new':
                $res = $produitRepository->findRecentProduct();
                break;
            case 'price_asc':
                $res = $produitRepository->price_asc();
                break;
            case 'price_desc':
                $res = $produitRepository->price_desc();
                break;
        }

        // $response = new Response(json_encode($res));
        // $response->headers->set('Content-Type', 'application/json');
        // dd($response);
        // dd($res);
        // $t = json_encode($res);
        // dd(json_encode($res));
        $json = $serializer->serialize($res, 'json', ['groups' => ['prod:read']]);
        // dd($this->json($res));
        // dd($t);
        // dd($json);
        return $this->json($json);
        // return $this->render('frontend/shoplist.html.twig', [
        //     'res' => $res
        // ]);
    }

    // #[ROUTE('/search/{val}', name: 'app_search_by', methods: ['GET'])]
    // public function search($val, ProduitRepository $produitRepository, SerializerInterface $serializer): JsonResponse
    // {
    //     // $search = $request->get('q');
    //     // dd($search);
    //     // return $this->render('');
    //     // if ($search) {
    //     $res = $produitRepository->findBySearch($val);

    //     // dd($res);
    //     $json = $serializer->serialize($res, 'json', ['groups' => ['prod:read']]);
    //     // $json = json_decode($json);
    //     return $this->json($json);
    // }
    //}























    #[Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN') or is_granted('ROLE_COMPTABLE')  ")]
    #[Route('/admin', name: 'app_home_admin')]
    public function index(UserRepository $userRepository, ProduitRepository $produitRepository): Response
    {

        $clientRecent = $userRepository->findRecentClient();
        $membreRecent = count($clientRecent);

        $clientTotal = $userRepository->findByRoles("ROLE_USER");
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
        $productRecent = $produitRepository->findRecentProduct();


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
    public function home(ProduitRepository $produitRepository, FeadBackRepository $feadBackRepository, ReductionRepository $reductionRepository, SerializerInterface $serializer): Response
    {
        $totalSalesMonth = $produitRepository->TOTALSALESMONTH(); // pour la partie admin
        // dd($totalSalesMonth[0]['total']);
        $MostSalesMonth = $produitRepository->findSalesMonth();
        $NewProduct = $produitRepository->findRecentProduct(); //produit arriver il y'a 2 weeks et maxREsult 10
        // dd($NewProduct);
        $bestSellers = $produitRepository->BestSellers();
        // dd($bestSellers);
        $plusVendus = $produitRepository->MostBuy();


        // $findsearch = $produitRepository->findBySearch('ome');
        // dd($findsearch);
        // dd($plusVendus);
        $produits = $produitRepository->findAll();

        $reviews = $feadBackRepository->findFeedback();
        // dd($reviews);

        $produits_reduction = $produitRepository->get_produit_reduction();
        $reductions = $reductionRepository->findAll();
        // dd($produits_reduction);
        // $json = $serializer->serialize($produits, 'json', ['groups' => ['prod:read']]);



        return $this->render('frontend/home.html.twig', [
            'produits' => $produits,
            'mostSaleMonth' => $MostSalesMonth,
            'NewProduct' => $NewProduct,
            'bestSellers' => $bestSellers,
            'reductions' => $reductions,
            'reviews' => $reviews

        ]);
    }





    #[Route('/panier/{id}/{slug}/{user_id}', name: 'app_panier', methods: ['GET'])]
    public function panier($id, $slug, $user_id, UserRepository $userRepository, ManagerRegistry $doctrine, ProduitRepository $produitRepository, PanierRepository $panierRepository): Response
    {

        // $entityManager = $doctrine->getManager();
        // $panier=new Panier();
        // $user=$userRepository->find($user_id);
        // $panier->setUser($user);
        // $entityManager->persist($panier);
        $panier = $panierRepository->findOneBy(['user' => $userRepository->find($user_id)]);

        // // actually executes the queries (i.e. the INSERT query)
        // $entityManager->flush();
        $panier_id = $panier->getId();

        // $val->array_push($produit->getId());

        //  $panier_id=$panier->getId();
        // $array_ids = [];
       
                // $produit=$produitRepository->find($d[$i]);
                // $panier->addProduit($produit);


                $panierRepository->add_to_produit_panier($panier_id, $id, $slug);
        
        //  dd("fin am3lm");      
        $panier_produit = $panierRepository->find_one_produit_panier($panier_id, $id);

        return $this->json($panier_produit);
        // return $this->redirectToRoute('app_panier_infos', ['id' => $user_id ], Response::HTTP_SEE_OTHER);


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


















    #[Route('/panier_edit/{id}/{slug}/{user_id}', name: 'app_panier_edit', methods: ['GET'])]
    public function panier_edit($id, $slug, $user_id, UserRepository $userRepository, ManagerRegistry $doctrine, ProduitRepository $produitRepository, PanierRepository $panierRepository): JsonResponse
    {

        // $entityManager = $doctrine->getManager();
        // $panier=new Panier();
        // $user=$userRepository->find($user_id);
        // $panier->setUser($user);
        // $entityManager->persist($panier);
        $panier = $panierRepository->findOneBy(['user' => $userRepository->find($user_id)]);

        // // actually executes the queries (i.e. the INSERT query)
        // $entityManager->flush();
        $panier_id = $panier->getId();

        // $val->array_push($produit->getId());
       
        //  $panier_id=$panier->getId();
        // $array_ids = [];
    
                // $produit=$produitRepository->find($d[$i]);
                // $panier->addProduit($produit);


                $panierRepository->edit_produit_panier($panier_id, $id, $slug);
       
        //  dd($panier);     
        $panier_produit = $panierRepository->find_one_produit_panier($panier_id, $id);

        return $this->json($panier_produit);


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


    #[Route('/panier_length/{id}', name: 'app_panier_length', methods: ['GET'])]
    public function panier_length(User $user, UserRepository $userRepository, ManagerRegistry $doctrine, ProduitRepository $produitRepository, PanierRepository $panierRepository): JsonResponse
    {

        $panier = $panierRepository->findOneBy(['user' => $user]);
           
        $panier_produit = $panierRepository->find_produit_panier($panier->getId());
        $obj = json_decode($panier_produit);
        
        return $this->json($obj);
    }






    #[Route('/panier_delete/{id}/{user_id}', name: 'app_panier_delete', methods: ['GET'])]
    public function panier_delete($id, $user_id, UserRepository $userRepository, ManagerRegistry $doctrine, ProduitRepository $produitRepository, PanierRepository $panierRepository): Response
    {

        // $entityManager = $doctrine->getManager();
        // $panier=new Panier();
        // $user=$userRepository->find($user_id);
        // $panier->setUser($user);
        // $entityManager->persist($panier);
        $panier = $panierRepository->findOneBy(['user' => $userRepository->find($user_id)]);

        // // actually executes the queries (i.e. the INSERT query)
        // $entityManager->flush();
        $panier_id = $panier->getId();

        // $val->array_push($produit->getId());
        // $d = str_split($id);

        //  $panier_id=$panier->getId();
        // $array_ids = [];
        // for ($i = 0; $i < count($d); $i++) {
        //     if ($d[$i] !== "," && $f[$i]!==","){
        // $produit=$produitRepository->find($d[$i]);
        // $panier->addProduit($produit);


        // $panierRepository->edit_produit_panier($panier_id,$d[$i],$f[$i]);




        // }
        // }
        //  dd($panier);     
        $panierRepository->delete_one_produit_panier($panier_id, $id);

        return $this->redirectToRoute('app_panier_infos', ['id' => $user_id], Response::HTTP_SEE_OTHER);


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