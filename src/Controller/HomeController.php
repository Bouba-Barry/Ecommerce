<?php

namespace App\Controller;

use doctrine;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Ville;
use App\Entity\Panier;
use App\Entity\Region;
use App\Entity\Produit;
use App\Entity\Attribut;
use App\Entity\Quantite;
use App\Entity\Categorie;
use App\Entity\Reduction;
use App\Entity\Variation;
use Doctrine\ORM\Mapping\Id;
use App\Entity\SousCategorie;
use App\Services\MailerService;
use App\Form\ForgotPasswordType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Gedmo\Mapping\Annotation\Tree;
use App\Repository\VilleRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\AttributRepository;
use App\Repository\FeadBackRepository;
use App\Repository\CategorieRepository;
use App\Repository\ReductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


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

    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/forgotpassword',name: 'app_forgot', methods: ['GET'])]
    public function forgot(MailerInterface $mailer):Response
    {
      
        // $user=new User();
        // $form = $this->createForm(ForgotPasswordType::class);
        // $form->handleRequest($request);
        // if($form->isSubmitted() && $form->isValid()){

        //     dd("ff");

        // }
   
      
         return $this->render('forgot.html.twig', [
        
           ]);
    }
    #[Route('/resetpassword/{email}',name: 'app_reset', methods: ['GET' , 'POST'])]
    public function reset($email,Request $request,UserPasswordHasherInterface $passwordHasher,UserRepository $userRepository,MailerInterface $mailer):Response
    {
      
        $user=$userRepository->findOneBy(['email'=>$email]);
        $form = $this->createForm(ForgotPasswordType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();
            // dd($user->getPassword());
            //   dd($user->getPassword());

            // this condition is needed because the 'brochure' field is not required
            // so the img must be processed only when a file is uploaded

            /** fin de l'upload du profile du user */

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);


            $userRepository->add($user, true);
            // $this->addFlash('success', 'Mot de passe a ete modifie avec succes');
            $array=0;
            foreach($user->getRoles() as $role){
                if($role=="ROLE_USER")
                $array=1;
            }
            if($array==1){
                return $this->redirectToRoute('app_user_login', [], Response::HTTP_SEE_OTHER);
            }
            else{
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }
        }
        
   
      
         return $this->renderForm('reset.html.twig', [
            'form' => $form
           ]);
    }




    #[Route('/email',name:'app_email',methods:['POST'])]
    public function sendEmail(MailerInterface $mailer,Request $request):JsonResponse
    {
       
        $email= $request->get('email');
        $random = random_int(1000, 1000000);
        $email = (new Email())
            ->from('oussabitarek123@gmail.com')
            ->to($email)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('réinitialisez votre mot de passe!')
            ->html('<p>ton  code : <strong>'. $random .'</strong></p>');

        // $mailer->send($email);
    //    if (count($this->getErrors()) > 0) {
    //          dd($this->getErrors());
    //      }
        $this->json($mailer->send($email));

        return $this->json($random);
        // ...
    }

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
    public function checkout(User $user, $slug, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine)
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


    #[Route('/getVilles', name: 'app_getVilles_edituser', methods: ['GET'])]
    public function getVillesedituser( SerializerInterface $serializer,VilleRepository $villeRepository ,ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {

        $villes = $villeRepository->findAll();
        $json = $serializer->serialize($villes, 'json', ['groups' => ['ville']]);
        $json = json_decode($json);
        return $this->json($json);
    }


    #[Route('/getProduits', name: 'app_get_produits', methods: ['GET'])]
    public function getProduits( SerializerInterface $serializer,VilleRepository $villeRepository ,ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {

        $produits = $produitRepository->findAll();
        $json = $serializer->serialize($produits, 'json', ['groups' => ['produit:read']]);
        $json = json_decode($json);
        return $this->json($json);
    }

    #[Route('/getReduction/{id}', name: 'app_get_reductions', methods: ['GET'])]
    public function getReductions(Reduction $reduction ,SerializerInterface $serializer,VilleRepository $villeRepository ,ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($reduction, 'json', ['groups' => ['reduction']]);
        $json = json_decode($json);
        return $this->json($json);
    }

    #[Route('/getCategorie/{id}', name: 'app_get_categorie', methods: ['GET'])]
    public function getcategorie(Categorie  $categorie ,SerializerInterface $serializer,VilleRepository $villeRepository ,ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($categorie, 'json', ['groups' => ['categorie']]);
        $json = json_decode($json);
        return $this->json($json);
    }


    #[Route('/getAttribut/{id}', name: 'app_get_attribut', methods: ['GET'])]
    public function getAttribut(Attribut $attribut ,SerializerInterface $serializer,VilleRepository $villeRepository ,ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($attribut, 'json', ['groups' => ['attribut']]);
        $json = json_decode($json);
        return $this->json($json);
    }

    #[Route('/getVariation/{id}', name: 'app_get_variation', methods: ['GET'])]
    public function getVariation(Variation $variation ,SerializerInterface $serializer,VilleRepository $villeRepository ,ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($variation, 'json', ['groups' => ['variation']]);
        $json = json_decode($json);
        return $this->json($json);
    }


    #[Route('/getSousCategorie/{id}', name: 'app_get_souscategorie', methods: ['GET'])]
    public function getsouscategorie(SousCategorie  $souscategorie ,SerializerInterface $serializer,VilleRepository $villeRepository ,ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($souscategorie, 'json', ['groups' => ['souscategorie']]);
        $json = json_decode($json);
        return $this->json($json);
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
    public function panier_infos(User $user, AttributRepository $attributRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine)
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

    #[Route('/getQuantite/{id}', name: 'app_get_Quantite', methods: ['GET'])]
    public function getQuantite(Quantite $quantite, SerializerInterface $serializer): JsonResponse
    {

        
        $json = $serializer->serialize($quantite, 'json', ['groups' => ['quantite:read']]);
        // dd($this->json($res));
        // dd($t);
        // dd($json);
        $json = json_decode($json);

        return $this->json($json);
    } 


    #[Route('/getImage/{id}', name: 'app_get_image', methods: ['GET'])]
    public function getImage(Image $image, SerializerInterface $serializer): JsonResponse
    {

        $json = $serializer->serialize($image, 'json', ['groups' => ['image:read']]);
        // dd($this->json($res));
        // dd($t);
        // dd($json);
        $json = json_decode($json);

        return $this->json($json);
    }
     
    
    #[Route('/getAttributs/{id}', name: 'app_get_attributs', methods: ['GET'])]
    public function getAttributs(Produit $produit, AttributRepository $attributRepository ,SerializerInterface $serializer): JsonResponse
    {

        $attributs=$produit->getAttributs();
        $json = $serializer->serialize($attributs, 'json', ['groups' => ['attribut:read']]);
        // dd($this->json($res));
        // dd($t);
        // dd($json);
        $json = json_decode($json);

        return $this->json($json);
    } 

    #[Route('/getVariations/{id}', name: 'app_get_variations', methods: ['GET'])]
    public function getVariations(Produit $produit, AttributRepository $attributRepository ,SerializerInterface $serializer): JsonResponse
    {

        $variations=$produit->getVariation();
        $json = $serializer->serialize($variations, 'json', ['groups' => ['variation:read']]);
        // dd($this->json($res));
        // dd($t);
        // dd($json);
        $json = json_decode($json);

        return $this->json($json);
    } 

    #[Route('/getUser/{id}', name: 'app_get_user', methods: ['GET'])]
    public function getuseredit(User $user, AttributRepository $attributRepository ,SerializerInterface $serializer): JsonResponse
    {

        $json = $serializer->serialize($user, 'json', ['groups' => ['user:read']]);
        // dd($this->json($res));
        // dd($t);
        // dd($json);
        $json = json_decode($json);

        return $this->json($json);
    } 

    
    #[Route('/getsouscategories', name: 'app_get_souscategories', methods: ['GET'])]
    public function getsouscategories(SerializerInterface $serializer,SousCategorieRepository $sousCategorieRepository): JsonResponse
    {

     
        // dd($this->json($res));
        // dd($t);
         $json=$sousCategorieRepository->findAll();
        // $json = json_decode($json);
        $json = $serializer->serialize($json, 'json', ['groups' => ['souscategorie:read']]);
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
    public function shop_details($id, CategorieRepository $categorieRepository, FeadBackRepository $feadBackRepository, SerializerInterface $serializer, ProduitRepository $produitRepository, SousCategorieRepository $sousCategorieRepository)
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
            'reviews' => $reviews,
            'categories' => $categorieRepository->findAll()
        ]);
    }

    #[ROUTE('/shoplist', name: 'app_home_shop')]
    public function shopList(ProduitRepository $produitRepository, Request $request): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('frontend/shoplist.html.twig', [
            'produits' => $produits,
            'size' => count($produits)
        ]);
    }

    #[ROUTE('/search', name: 'app_search_shop', methods: ['GET', 'POST'])]
    public function shoplist_search(SerializerInterface $serializer, ProduitRepository $produitRepository, Request $request)
    {
        $value = $request->get('searchInput');
        $res = $produitRepository->findBySearch($value);
        // dd($res);

        $session = $this->requestStack->getSession();
        $session->set('valSearch', $value);

        $json = $serializer->serialize($res, 'json', ['groups' => ['prod:read']]);
        $json = json_decode($json);
        return $this->render('frontend/shoplist_search.html.twig', [
            'produits' => $json,
            'size' => count($json)
        ]);
    }

    #[ROUTE('/TrieSearch', name: 'app_trie_search_shop', methods: ['GET', 'POST'])]
    public function Sort_Search(SerializerInterface $serializer, ProduitRepository $produitRepository, Request $request): JsonResponse
    {
        $choice = $request->get('choice_val');

        $session = $this->requestStack->getSession();
        $value = $session->get('valSearch');

        $res = $produitRepository->findBySearch($value);

        $json = $serializer->serialize($res, 'json', ['groups' => ['prod:read']]);
        $json = json_decode($json);
        $tab = array();
        for ($i = 0; $i < count($json); $i++) {
            array_push($tab, $json[$i]->id);
        }

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
                    $ret = $produitRepository->find_price_desc_inSearch($tab);
                    break;
            }
            $ret = $serializer->serialize($ret, 'json', ['groups' => ['prod:read']]);
            return $this->json($ret);
        }
    }

    #[ROUTE('/shortProduct/{val}', name: 'app_short_by', methods: ['GET'])]
    public function shortBy($val,  SerializerInterface $serializer, ProduitRepository $produitRepository): JsonResponse
    {
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
        $json = $serializer->serialize($res, 'json', ['groups' => ['prod:read']]);
        return $this->json($json);
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

        if ($totalClient > 0) {


            $pourcentage = ($membreRecent * 100) / $totalClient;
        } else {
            $pourcentage = 0;
        }
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
    public function home(CategorieRepository $categorieRepository, SousCategorieRepository $sousCat, ProduitRepository $produitRepository, FeadBackRepository $feadBackRepository, ReductionRepository $reductionRepository, SerializerInterface $serializer): Response
    {
        $NewProducts = $produitRepository->findRecentProduct(); //produit arriver il y'a 2 weeks et maxREsult 10
        // dd($NewProducts);
        $bestSellers = $produitRepository->BestSellers();
        // dd($bestSellers);
        $plusVendus = $produitRepository->MostBuy();
        $produits = $produitRepository->findAll();

        $reviews = $feadBackRepository->findFeedback();
        // dd($reviews);

        $produits_reduction = $produitRepository->get_produit_reduction();

        $reductions = $reductionRepository->findAll();
        // dd($produits_reduction);
        // $json = $serializer->serialize($produits, 'json', ['groups' => ['prod:read']]);
        $SouscatePopulaire = $produitRepository->findPopularSousCategory();

        // dd($SouscatePopulaire);
        $slideProducts = $produitRepository->findBy(['sous_categorie' => $SouscatePopulaire[0]['id']], ['createAt' => 'DESC'], 4);

        // dd($slideProducts);

        // $max = $produitRepository->findOneBy([], ['id' => 'desc']);
        // $max = $max->getId();
        // $min = $produitRepository->findOneBy([], ['id' => 'ASC']);
        // $min = $min->getId();
        $popular_products = $produitRepository->PopularProducts_This_Month();
        // dd($popular_products);
        $categories = $categorieRepository->findAll();
        $marque = $sousCat->findAll();
        return $this->render('frontend/home.html.twig', [
            'produits' => $produits,
            'NewProducts' => $NewProducts,
            'bestSellers' => $bestSellers,
            'reductions' => $reductions,
            'reviews' => $reviews,
            'plusVendu' => $plusVendus,
            'sous_categories' => $SouscatePopulaire,
            'categories' => $categories,
            'marques' => $marque,
            'popular_products_month' => $popular_products
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
        // dd($panier_id);
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

    #[Route('/sous_categorie/{id}', name: 'app_prod_sous_cat', methods: ['GET'])]
    public function ProductBySousCategory($id, ProduitRepository $prod, SousCategorieRepository $sousCategorieRepository)
    {
        $products = $prod->findProductsBySousCategory($id);
        $size = count($products);
        $session = $this->requestStack->getSession();
        $session->set('idSousCat', $id);

        $main = $sousCategorieRepository->findOneBy(['id' => $id]);
        return $this->render('frontend/sous_categorie.html.twig', [
            'products' => $products,
            'main' => $main,
            'size' => $size
        ]);
    }

    #[Route('/category-product/{id}', name: 'app_prod_by_cate', methods: ['GET'])]
    public function prodByCategory($id, CategorieRepository $categorieRepository, ProduitRepository $produitRepository)
    {
        // dd($categorieRepository->findProductsByCategory($id));
        $produits = $produitRepository->findProductsByCategory($id);
        $session = $this->requestStack->getSession();
        $session->set('idCat', $id);
        $size = count($produits);
        // dd($pan->findMostViewMonth());


        $cat = $categorieRepository->findOneBy(['id' => $id]);
        return $this->render('frontend/produit_by_category.html.twig', [
            'produits' => $produits,
            'cat' => $cat,
            'size' => $size
        ]);
    }
    #[Route('/TrieCategory', name: 'app_sort_in_cate', methods: ['POST'])]
    public function Trie_Cat_Produit(SerializerInterface $serializer, CategorieRepository $categorieRepository, ProduitRepository $produitRepository, Request $request): JsonResponse
    {
        $choice = $request->get('Le_choix');
        $session = $this->requestStack->getSession();
        $value = $session->get('idCat');

        $res = $produitRepository->findProductsByCategory($value);

        $json = $serializer->serialize($res, 'json', ['groups' => ['prod:read']]);
        $json = json_decode($json);
        $tab = array();
        for ($i = 0; $i < count($json); $i++) {
            array_push($tab, $json[$i]->id);
        }
        // dd($tab);
        // $valll = $produitRepository->findMostPopulareInSearch($tab);
        // dd($valll);
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
                    $ret = $produitRepository->find_price_desc_inSearch($tab);
                    break;
            }
            $ret = $serializer->serialize($ret, 'json', ['groups' => ['prod:read']]);
            return $this->json($ret);
        }
    }

    #[Route('/TrieSousCategorie', name: 'app_sort_Sous_cate', methods: ['POST'])]
    public function Trie_SousCategorie(SerializerInterface $serializer, SousCategorieRepository $sousCategorieRepository, ProduitRepository $produitRepository, Request $request): JsonResponse
    {
        $choice = $request->get('choix');
        $session = $this->requestStack->getSession();
        $value = $session->get('idSousCat');

        $res = $produitRepository->findProductsBySousCategory($value);

        $json = $serializer->serialize($res, 'json', ['groups' => ['prod:read']]);
        $json = json_decode($json);
        $tab = array();
        for ($i = 0; $i < count($json); $i++) {
            array_push($tab, $json[$i]->id);
        }

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
                    $ret = $produitRepository->find_price_desc_inSearch($tab);
                    break;
            }
            $ret = $serializer->serialize($ret, 'json', ['groups' => ['prod:read']]);
            return $this->json($ret);
        }
    }
}