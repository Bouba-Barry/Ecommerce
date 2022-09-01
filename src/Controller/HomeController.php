<?php

namespace App\Controller;

use doctrine;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Slide;
use App\Entity\Ville;
use App\Entity\Panier;
use App\Entity\Region;
use App\Entity\Produit;
use App\Data\FilterData;
use App\Entity\Attribut;
use App\Entity\Quantite;
use App\Form\FilterForm;
use App\Form\FilterType;
use App\Form\SearchType;
use App\Entity\Categorie;
use App\Entity\Lien;
use App\Entity\Reduction;
use App\Entity\Variation;
use App\Form\FilterCateType;
use Doctrine\ORM\Mapping\Id;
use App\Entity\SousCategorie;
use App\Services\MailerService;
use App\Form\ForgotPasswordType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Gedmo\Mapping\Annotation\Tree;
use App\Repository\SlideRepository;
use App\Repository\VilleRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\AttributRepository;
use App\Repository\FeadBackRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommandeRepository;
use App\Repository\QuantiteRepository;
use App\Repository\ReductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use App\Repository\SousCategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    private $paginator;

    public function __construct(RequestStack $requestStack, PaginatorInterface $paginator)
    {
        $this->requestStack = $requestStack;
        $this->paginator = $paginator;
    }

    #[Route('/forgotpassword', name: 'app_forgot', methods: ['GET'])]
    public function forgot(MailerInterface $mailer): Response
    {

        // $user=new User();
        // $form = $this->createForm(ForgotPasswordType::class);
        // $form->handleRequest($request);
        // if($form->isSubmitted() && $form->isValid()){

        //     dd("ff");

        // }


        return $this->render('forgot.html.twig', []);
    }



    #[Route('/slide', name: 'app_slide_gerer', methods: ['GET'])]
    public function gererslide(MailerInterface $mailer): Response
    {

        $form = $this->createForm(ForgotPasswordType::class);


        return $this->render('forgot.html.twig', []);
    }




    #[Route('/resetpassword/{email}', name: 'app_reset', methods: ['GET', 'POST'])]
    public function reset($email, Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, MailerInterface $mailer): Response
    {

        $user = $userRepository->findOneBy(['email' => $email]);
        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

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
            $array = 0;
            foreach ($user->getRoles() as $role) {
                if ($role == "ROLE_USER")
                    $array = 1;
            }
            if ($array == 1) {
                return $this->redirectToRoute('app_user_login', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }
        }



        return $this->renderForm('reset.html.twig', [
            'form' => $form
        ]);
    }




    #[Route('/email', name: 'app_email', methods: ['POST'])]
    public function sendEmail(MailerInterface $mailer, Request $request): JsonResponse
    {

        $email = $request->get('email');
        $random = random_int(1000, 1000000);
        $email = (new Email())
            ->from('oussabitarek123@gmail.com')
            ->to($email)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('réinitialisez votre mot de passe!')
            ->html('<p>ton  code : <strong>' . $random . '</strong></p>');

        // $mailer->send($email);
        //    if (count($this->getErrors()) > 0) {
        //          dd($this->getErrors());
        //      }
        $mailer->send($email);

        return $this->json($random);
        // ...
    }

    #[Route('/discover_product/{id}', name: 'app_discover_product', methods: ['GET'])]
    public function discover_product($id, Request $request, ReductionRepository $reductionRepository, ProduitRepository $produitRepository)
    {

        // $products = $reduction->getProduits();  
        $data = new FilterData();
        $form = $this->createForm(SearchType::class, $data, [
            'method' => 'GET',
        ]);

        // $parametersToValidate = $request->query->all();
        $form->handleRequest($request);
        $produits = $produitRepository->findRedByProducts($data, $id);
        // dd($produits);
        $size = count($produits);
        if ($request->isXmlHttpRequest()) {

            return new JsonResponse([
                'content' => $this->renderView('frontend/reduction/_produit.html.twig', ['produits' => $produits])
            ]);
        }
        return $this->render('frontend/reduction/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
            'size' => $size,
        ]);
    }






    #[Route('/checkout/{id}', name: 'app_checkout', methods: ['GET'])]
    public function checkout(User $user, Request $request, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine)
    {
        // dd($slug);
        $slug = $request->cookies->get('total');
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
    public function getVillesedituser(SerializerInterface $serializer, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {

        $villes = $villeRepository->findAll();
        $json = $serializer->serialize($villes, 'json', ['groups' => ['ville']]);
        $json = json_decode($json);
        return $this->json($json);
    }


    #[Route('/getProduits', name: 'app_get_produits', methods: ['GET'])]
    public function getProduits(SerializerInterface $serializer, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {

        $produits = $produitRepository->findAll();
        $json = $serializer->serialize($produits, 'json', ['groups' => ['produit:read']]);
        $json = json_decode($json);
        return $this->json($json);
    }

    #[Route('/getSlides', name: 'app_get_slides', methods: ['GET'])]
    public function slides(SerializerInterface $serializer, SlideRepository $slideRepository, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {

        $slides = $slideRepository->findAll();
        $json = $serializer->serialize($slides, 'json', ['groups' => ['slide']]);
        $json = json_decode($json);
        return $this->json($json);
    }

    #[Route('/getReduction/{id}', name: 'app_get_reductions', methods: ['GET'])]
    public function getReductions(Reduction $reduction, SerializerInterface $serializer, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($reduction, 'json', ['groups' => ['reduction']]);
        $json = json_decode($json);
        return $this->json($json);
    }


    #[Route('/getSlide/{id}', name: 'app_get_slide', methods: ['GET'])]
    public function getslide(Slide $slide, SerializerInterface $serializer, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($slide, 'json', ['groups' => ['slide']]);
        $json = json_decode($json);
        return $this->json($json);
    }



    #[Route('/setchoisiSlide/{id}', name: 'app_setchoisi_slide', methods: ['GET'])]
    public function setchoisislide(Slide $slide, SlideRepository $slideRepository, SerializerInterface $serializer, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {


        $slide->setChoisi("oui");
        $slideRepository->add($slide, true);
        foreach ($slideRepository->findAll() as $slid) {
            if ($slid != $slide) {
                $slid->setChoisi("non");
                $slideRepository->add($slid, true);
            }
        }

        return $this->json(1);
    }



    #[Route('/getlien/{id}', name: 'app_get_lien', methods: ['GET'])]
    public function getlien(Lien $lien, SerializerInterface $serializer, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($lien, 'json', ['groups' => ['lien']]);
        $json = json_decode($json);
        return $this->json($json);
    }


    #[Route('/getCategorie/{id}', name: 'app_get_categorie', methods: ['GET'])]
    public function getcategorie(Categorie  $categorie, SerializerInterface $serializer, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($categorie, 'json', ['groups' => ['categorie']]);
        $json = json_decode($json);
        return $this->json($json);
    }

    //     $villes = $villeRepository->findAll();
    //     $json = $serializer->serialize($villes, 'json', ['groups' => ['ville']]);
    //     $json = json_decode($json);
    //     return $this->json($json);
    // }

    #[Route('/getAttribut/{id}', name: 'app_get_attribut', methods: ['GET'])]
    public function getAttribut(Attribut $attribut, SerializerInterface $serializer, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($attribut, 'json', ['groups' => ['attribut']]);
        $json = json_decode($json);
        return $this->json($json);
    }

    #[Route('/getVariation/{id}', name: 'app_get_variation', methods: ['GET'])]
    public function getVariation(Variation $variation, SerializerInterface $serializer, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
    {
        $json = $serializer->serialize($variation, 'json', ['groups' => ['variation']]);
        $json = json_decode($json);
        return $this->json($json);
    }

    //     $produits = $produitRepository->findAll();
    //     $json = $serializer->serialize($produits, 'json', ['groups' => ['produit:read']]);
    //     $json = json_decode($json);
    //     return $this->json($json);
    // }

    #[Route('/getSousCategorie/{id}', name: 'app_get_souscategorie', methods: ['GET'])]
    public function getsouscategorie(SousCategorie  $souscategorie, SerializerInterface $serializer, VilleRepository $villeRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine): JsonResponse
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
    public function panier_infos(User $user, QuantiteRepository $quantiteRepository, AttributRepository $attributRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository, ManagerRegistry $doctrine)
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
            'quantities' => $quantiteRepository->findAll(),
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
    public function getAttributs(Produit $produit, AttributRepository $attributRepository, SerializerInterface $serializer): JsonResponse
    {

        $attributs = $produit->getAttributs();
        $json = $serializer->serialize($attributs, 'json', ['groups' => ['attribut:read']]);
        // dd($this->json($res));
        // dd($t);
        // dd($json);
        $json = json_decode($json);

        return $this->json($json);
    }
    #[Route('/getAttributs', name: 'app_get_all_attributs', methods: ['GET'])]
    public function getallAttributs(AttributRepository $attributRepository, SerializerInterface $serializer): JsonResponse
    {

        $attributs = $attributRepository->findAll();
        $json = $serializer->serialize($attributs, 'json', ['groups' => ['attribut:read']]);
        // dd($this->json($res));
        // dd($t);
        // dd($json);
        $json = json_decode($json);

        return $this->json($json);
    }



    #[Route('/getVariations/{id}', name: 'app_get_variations', methods: ['GET'])]
    public function getVariations(Produit $produit, AttributRepository $attributRepository, SerializerInterface $serializer): JsonResponse
    {

        $variations = $produit->getVariation();
        $json = $serializer->serialize($variations, 'json', ['groups' => ['variation:read']]);
        // dd($this->json($res));
        // dd($t);
        // dd($json);
        $json = json_decode($json);

        return $this->json($json);
    }

    #[Route('/getUser/{id}', name: 'app_get_user', methods: ['GET'])]
    public function getuseredit(User $user, AttributRepository $attributRepository, SerializerInterface $serializer): JsonResponse
    {

        $json = $serializer->serialize($user, 'json', ['groups' => ['user:read']]);

        $json = json_decode($json);

        return $this->json($json);
    }


    #[Route('/getsouscategories', name: 'app_get_souscategories', methods: ['GET'])]
    public function getsouscategories(SerializerInterface $serializer, SousCategorieRepository $sousCategorieRepository): JsonResponse
    {


        // dd($this->json($res));
        // dd($t);
        $json = $sousCategorieRepository->findAll();
        // $json = json_decode($json);
        $json = $serializer->serialize($json, 'json', ['groups' => ['souscategorie:read']]);
        $json = json_decode($json);

        return $this->json($json);
    }





    #[Route('/delete_reduction', name: 'app_delete_reduction', methods: ['GET'])]
    public function delete_reduction(ReductionRepository $reductionRepository, QuantiteRepository $quantiteRepository, ProduitRepository $produitRepository): JsonResponse
    {

        $reduction =  $reductionRepository->get_reduction_willfinish();
        // $reduction =  $reductionRepository->find(10);
        foreach ($reduction as $red) {
            $prix = str_replace("%", "", $red->getPourcentage());
            foreach ($red->getProduits() as $produit) {
                if ($produit->gettype() == "stable") {
                    $produit->setNouveauPrix(NULL);
                } else {
                    foreach ($produit->getQuantites() as $quantite) {
                        $quantite->setPrix($quantite->getPrix() + ($prix * ($quantite->getPrix() / ($prix / 100)) / 100));
                        $quantiteRepository->add($quantite, true);
                    }
                }
                $produitRepository->add($produit, true);
            }
        }


        $reductionRepository->delete_reduction();
        return $this->json(1);
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

    #[ROUTE('/shoplist', name: 'app_home_shop', methods: ['GET', 'POST'])]
    public function shopList(ProduitRepository $produitRepository, Request $request, SerializerInterface $serializer)
    {

        $data = new FilterData();
        $form = $this->createForm(FilterType::class, $data, [
            'action' => 'http://127.0.0.1:8000/shoplist',
            'method' => 'GET',
        ]);
        // dd($data);
        // if ($request->get('filter_form[q]')) {
        //     dd($request->query->all());
        // }
        $parametersToValidate = $request->query->all();
        // if ($parametersToValidate) {
        //     dd($parametersToValidate);
        // }
        $form->handleRequest($request);
        $produits = $produitRepository->findByFilter($data);
        // dd($produits);
        $size = count($produits);
        // dd($size);
        if ($request->isXmlHttpRequest()) {

            // $data = new FilterData();
            // $form = $this->createForm(FilterType::class, $data);
            // $prods = $produitRepository->findByFilter($data);
            // $form->handleRequest($request);
            return new JsonResponse([
                'content' => $this->renderView('frontend/boutique/_produits.html.twig', ['produits' => $produits])
            ]);
            // $json = $serializer->serialize($produits, 'json', ['groups' => ['prod:read']]);
            // return $this->json($json);
        }
        return $this->render('frontend/boutique/shoplist.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
        ]);
    }
    #[ROUTE('/search', name: 'app_search_shop', methods: ['GET', 'POST'])]
    public function shoplist_search(ProduitRepository $produitRepository, Request $request, CategorieRepository $categorieRepository)
    {
        $value = $request->get('q');
        $data = new FilterData();

        $data->q = $value;
        $form = $this->createForm(FilterType::class, $data, [
            'method' => 'GET',
        ]);

        // $parametersToValidate = $request->query->all();
        $form->handleRequest($request);
        $produits = $produitRepository->findBySearch($data, $value);
        $size = count($produits);
        if ($request->isXmlHttpRequest()) {

            return new JsonResponse([
                'content' => $this->renderView('frontend/search/_produit.html.twig', ['produits' => $produits])
            ]);
        }
        return $this->render('frontend/search/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
            'size' => $size,
            'value' => $value
        ]);
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
    public function index(UserRepository $userRepository, CommandeRepository $commandeRepository, ProduitRepository $produitRepository): Response
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

        $bestEmployer = $userRepository->findBestEmployer();
        $salesMonth = $produitRepository->findSaleMonth();
        // dd($salesMonth[0]["revenu"]);
        // dd($bestEmployer);

        $cmdTraiter = count($commandeRepository->findCmdStat("traitée"));
        $cmdAnnuler = count($commandeRepository->findCmdStat("annulée"));
        // produit ajouter il y'a au max un mois
        $productRecent = $produitRepository->findRecentProduct();


        return $this->render('home/dashbord.html.twig', [
            'clients' => $clientRecent,
            'produits' => $produits,
            'produitRecents' => $productRecent,
            'totalClient' => $totalClient,
            'RecentClient' => $membreRecent,
            'pourClient' => $pourcentage,
            'bestWorker' => $bestEmployer,
            'cmdTraiter' => $cmdTraiter,
            'cmdAnnuler' => $cmdAnnuler,
            'revenu' => $salesMonth[0]["revenu"]
        ]);
    }


    #[Route('', name: 'app_home')]
    public function home(CategorieRepository $categorieRepository, SlideRepository $slideRepository, SousCategorieRepository $sousCat, ProduitRepository $produitRepository, FeadBackRepository $feadBackRepository, ReductionRepository $reductionRepository, SerializerInterface $serializer): Response
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

        $session = $this->requestStack->getSession();
        $session->set('red', count($reductions));
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
        $categories = $categorieRepository->findBy([], ['create_at' => 'ASC'], 4);
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
            'popular_products_month' => $popular_products,
            'slide' => $slideRepository->findOneBy(['choisi' => 'oui'])
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
    public function ProductBySousCategory($id, Request $request, SerializerInterface $serializer, ProduitRepository $prod, SousCategorieRepository $sousCategorieRepository)
    {
        $data = new FilterData();
        $form = $this->createForm(FilterCateType::class, $data, [
            'method' => 'GET',
        ]);

        // $parametersToValidate = $request->query->all();
        $main = $sousCategorieRepository->findOneBy(['id' => $id]);
        $form->handleRequest($request);
        $produits = $prod->findSousCateByFilter($data, $id);
        $size = count($produits);
        if ($request->isXmlHttpRequest()) {

            return new JsonResponse([
                'content' => $this->renderView('frontend/populaire_sous_categorie/_produit.html.twig', ['produits' => $produits])
            ]);
        }
        return $this->render('frontend/populaire_sous_categorie/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
            'size' => $size,
            'main' => $main
        ]);
    }


    #[Route('/category-product/{id}', name: 'app_prod_by_cate', methods: ['GET'])]
    public function prodByCategory($id, Request $request, CategorieRepository $categorieRepository, ProduitRepository $produitRepository)
    {
        $data = new FilterData();
        $form = $this->createForm(FilterCateType::class, $data, [
            'method' => 'GET',
        ]);

        // $parametersToValidate = $request->query->all();
        $main = $categorieRepository->findOneBy(['id' => $id]);
        $form->handleRequest($request);
        $produits = $produitRepository->findCateByFilter($data, $id);
        $size = count($produits);
        if ($request->isXmlHttpRequest()) {

            return new JsonResponse([
                'content' => $this->renderView('frontend/categorie/_produit.html.twig', ['produits' => $produits])
            ]);
        }
        return $this->render('frontend/categorie/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
            'size' => $size,
            'main' => $main
        ]);
    }

    #[Route('/category', name: 'app_cate', methods: ['GET'])]
    public function allCategory(CategorieRepository $categorieRepository)
    {
        $category = $categorieRepository->findAll();

        return $this->render('frontend/AllCategorie.html.twig', [
            'categories' => $category
        ]);
    }

    #[Route('/popular_products', name: 'app_famous', methods: ['GET', 'POST'])]
    public function popular_product(ProduitRepository $prod, Request $request)
    {
        $data = new FilterData();
        $form = $this->createForm(FilterType::class, $data);
        // $parametersToValidate = $request->query->all();
        $form->handleRequest($request);
        $produits = $prod->PopularProd_Month($data);

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'content' => $this->renderView('frontend/popular_product/_produit.html.twig', ['produits' => $produits])
            ]);
        }
        return $this->render('frontend/popular_product/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
        ]);
    }
}