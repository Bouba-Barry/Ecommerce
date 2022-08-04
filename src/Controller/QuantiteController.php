<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Quantite;
use App\Entity\Variation;
use App\Form\QuantiteType;
use App\Form\EditQuantiteType;
use App\Repository\UserRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\QuantiteRepository;
use App\Repository\VariationRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/quantite')]
class QuantiteController extends AbstractController
{

    #[Route('/panier_delete_variations/{id}/{user_id}/{variations}', name: 'app_panier_delete_variations', methods: ['GET'])]
    public function panier_delete($id, $user_id,$variations,UserRepository $userRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository): Response
    {
      
        $panier = $panierRepository->findOneBy(['user' => $userRepository->find($user_id)]);
        $panier_id = $panier->getId();
        $panierRepository->delete_one_produit_panier_variations($panier_id, $id,$variations);
        return $this->redirectToRoute('app_panier_infos', ['id' => $user_id], Response::HTTP_SEE_OTHER);

    }

    #[Route('/variations_panier_check/{id}/{variations}', name: 'app_variations_panier_check', methods: ['GET'])]
    public function variations_panier_check($id,$variations,QuantiteRepository $quantiteRepository,ProduitRepository $produitRepository,SerializerInterface $serializer): JsonResponse
    {

        $variations=str_replace("[\"","",$variations);
        $variations=str_replace("\"]","",$variations);
        $variations=str_replace("\"","",$variations);
        $produit=$produitRepository->find($id);
        $array=[];
        $array=explode(',',$variations);
        $array=json_encode($array); 
        
        
       $produit_quantite= $quantiteRepository->findquantite_produit($array,$id)   ;
    //    $produit_quantite=json_decode($produit_quantite);
        return $this->json($produit_quantite) ;
    }


    #[Route('/variations_produit/{id}', name: 'app_variations_produit', methods: ['GET'])]
    public function variations_produit(Produit $produit,ProduitRepository $produitRepository,SerializerInterface $serializer): JsonResponse
    {
        $variations_produit=$serializer->serialize($produit->getVariation(), 'json', ['groups' => ['variation']]);
        $json=json_decode($variations_produit);
        
        return $this->json($json) ;
    }


 
    #[Route('/panier/{id}/{slug}/{variations}/{user_id}', name: 'app_panier_quantite', methods: ['GET'])]
    public function panier_quantite($id, $slug, $variations ,$user_id, UserRepository $userRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository)
    {

        $array=[];
        $array=explode(',',$variations);
        $array=json_encode($array); 
        $panier = $panierRepository->findOneBy(['user' => $userRepository->find($user_id)]);
        $panier_id = $panier->getId();
        $panierRepository->add_to_produit_panier_variations($panier_id, $id, $slug,$array);
        // $panier_produit = $panierRepository->find_one_produit_panier($panier_id, $id);


        return true;
    
    }

    #[Route('/panier_edit/{id}/{slug}/{variations}/{user_id}', name: 'app_panier_quantite_edit', methods: ['GET'])]
    public function panier_quantite_edit($id, $slug, $variations ,$user_id, UserRepository $userRepository, ProduitRepository $produitRepository, PanierRepository $panierRepository)
    {

        
        $array=[];
        $array=explode(',',$variations);
        $array=json_encode($array); 
        

        $panier = $panierRepository->findOneBy(['user' => $userRepository->find($user_id)]);
        $panier_id = $panier->getId();

        $panierRepository->edit_produit_panier_variations($panier_id, $id, $slug,$array);

        // $panier_produit = $panierRepository->find_one_produit_panier($panier_id, $id);

        return true;
    
    }








    #[Route('/panier/check/{id}/{slug}', name: 'app_panier_check_variations', methods: ['GET'])]
    public function panier_check($id,$slug,UserRepository $userRepository,PanierRepository $panierRepository,QuantiteRepository $quantiteRepository,ProduitRepository $produitRepository,SerializerInterface $serializer):JsonResponse
    {
       
        $user=$userRepository->find($slug);
        
        $array=[];
        $array=explode(',',$id);
        $array=json_encode($array); 
        // $variations1=[];
        // foreach($array as $d ){
        //     array_push($variations1,$d);
        // }
        // dd($variations1);
        $panier = $panierRepository->findOneBy(['user' => $user]);
        $quantite=$panierRepository->findquantite_panier($array,$panier->getId());

        return $this->json($quantite);
        // $variations_produit=$serializer->serialize($produit->getVariation(), 'json', ['groups' => ['variation']]);
        // $json=json_decode($variations_produit);
        
        // return $this->json($json) ;
    }





    #[Route('/check/{id}', name: 'app_variations_produit_check', methods: ['GET'])]
    public function check($id,QuantiteRepository $quantiteRepository,ProduitRepository $produitRepository,SerializerInterface $serializer):JsonResponse
    {
       
        
        $array=[];
        $array=explode(',',$id);
        $array=json_encode($array); 
        // $variations1=[];
        // foreach($array as $d ){
        //     array_push($variations1,$d);
        // }
        // dd($variations1);
        $quantite=$quantiteRepository->findquantite($array);
        return $this->json($quantite);
        // $variations_produit=$serializer->serialize($produit->getVariation(), 'json', ['groups' => ['variation']]);
        // $json=json_decode($variations_produit);
        
        // return $this->json($json) ;
    }




    #[Route('/admin', name: 'app_quantite_index', methods: ['GET'])]
    public function index(QuantiteRepository $quantiteRepository): Response
    {
        
        
        return $this->render('quantite/index.html.twig', [
            'quantites' => $quantiteRepository->findAll(),
        ]);
    }

    #[Route('/admin/new', name: 'app_quantite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuantiteRepository $quantiteRepository): Response
    {
        $quantite = new Quantite();
        $form = $this->createForm(QuantiteType::class, $quantite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             $quantite=$form->getData();
            //  dd($form->get('variations')->getData());

            $array=[];
             foreach( $form->get('variations')->getData() as $variations_produit ){
                array_push($array,$variations_produit->getNom());

             }
             

                // dd($array);
             $quantite->setVariations($array);
            $quantiteRepository->add($quantite, true);

            return $this->redirectToRoute('app_quantite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quantite/new.html.twig', [
            'quantite' => $quantite,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_quantite_show', methods: ['GET'])]
    public function show(Quantite $quantite): Response
    {
        return $this->render('quantite/show.html.twig', [
            'quantite' => $quantite,
        ]);
    }

    #[Route('/admin/edit/{id}', name: 'app_quantite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quantite $quantite,VariationRepository $variationRepository ,QuantiteRepository $quantiteRepository): Response
    {
       
        
        // $quantite=$quantiteRepository->find(4);

        // dd($quantite->getVariations());
         $array1=[];
        foreach($quantite->getVariations() as $obj){
          
            array_push($array1,$variationRepository->findBy(['nom' => $obj ]));

        }
        $array2=[];
         for($i=0;$i<count($array1);$i++){
            $array2[$i]=$array1[$i][0];
         }
    //    dd($array2);
        $quantite->setVariations($array2);
               
        $form = $this->createForm(EditQuantiteType::class,$quantite,array('id' =>$quantite->getProduit()->getId()));
        
        $form->handleRequest($request);
        // dd( $form->get('variations')->getData());
        if ($form->isSubmitted() && $form->isValid()) {
            // $quantite=$form->getData();
             $array=[];
            //  dd( $form->get('variations')->getData());
             foreach( $form->get('variations')->getData() as $variations_produit ){
                array_push($array,$variations_produit->getNom());
               
             }
             
                // dd($array);
            $quantite->setVariations($array);
            $quantiteRepository->add($quantite, true);

            return $this->redirectToRoute('app_quantite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quantite/edit.html.twig', [
            'quantite' => $quantite,
            'form' => $form,
        ]);
    }

    #[Route('admin/{id}', name: 'app_quantite_delete', methods: ['POST'])]
    public function delete(Request $request, Quantite $quantite, QuantiteRepository $quantiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quantite->getId(), $request->request->get('_token'))) {
            $quantiteRepository->remove($quantite, true);
        }

        return $this->redirectToRoute('app_quantite_index', [], Response::HTTP_SEE_OTHER);
    }
}
