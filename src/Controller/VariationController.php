<?php

namespace App\Controller;

use App\Entity\Attribut;
use App\Entity\Variation;
use App\Form\VariationType;
use App\Repository\AttributRepository;
use App\Repository\VariationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Security("is_granted('ROLE_ADMIN')")]
#[Route('/admin/variation')]
class VariationController extends AbstractController
{
    #[Route('/', name: 'app_variation_index', methods: ['GET'])]
    public function index(VariationRepository $variationRepository): Response
    {
        return $this->render('variation/index.html.twig', [
            'variations' => $variationRepository->findAll(),
        ]);
    }

    #[Route('/attribut/{id}', name: 'app_variation_index_attribut', methods: ['GET'])]
    public function variation_attribut(Attribut $attribut,VariationRepository $variationRepository): Response
    {
        return $this->render('variation/index_attribut.html.twig', [
            'variations' => $variationRepository->findBy(['attribut' => $attribut ]),
            'attribut' => $attribut,
        ]);
    }



    



    #[Route('/corbeille', name: 'app_variation_corbeille', methods: ['GET'])]
    public function corbeille(VariationRepository $variationRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        
        $variations=$variationRepository->findcorbeille();
        

        return $this->render('variation/corbeille.html.twig', [
            'variations' => $variations
        ]);
    }

    #[Route('/restore/{id}', name: 'app_variation_restore', methods: ['GET'])]
    public function restore($id,VariationRepository $variationRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        $variation=$variationRepository->find($id);
        $variation->setDeletedAt(Null);
        $em->persist($variation);
        $em->flush();        
        $variations=$variationRepository->findcorbeille();
        return $this->render('variation/corbeille.html.twig', [
            'variations' => $variations
        ]);
    }

    #[Route('/delete_from_corbeille/{id}', name: 'app_variation_delete_fromcorbeille', methods: ['GET'])]
    public function deleteforce($id,VariationRepository $variationRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
       
        //  $em->getFilters()->disable('softdeleteable');
        //  $user=$userRepository->find($id);
         $variationRepository->deletefromtrash($id);
             
        $variations=$variationRepository->findcorbeille();
        return $this->render('variation/corbeille.html.twig', [
            'variations' => $variations
        ]);
    }
    



    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/new/aside/variation/{id}', name: 'app_variation_new_aside_variation', methods: ['GET', 'POST'])]
    public function new_aside_variation( $id,Request $request , AttributRepository $attributRepository ,VariationRepository $variationRepository): Response
    {
        $variation = new Variation();
        $variation->setAttribut($attributRepository->find($id));
        $form = $this->createForm(VariationType::class, $variation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->addFlash('variation', 'Variation Ajoute avec succes');

            $variation_produit = $form->get('produits')->getData();

            foreach ($variation_produit as $var) {
                $variation->addProduit($var);
            }


            $variationRepository->add($variation, true);

            return $this->redirectToRoute('app_variation_new_aside_variation', ['id'=> $variation->getAttribut()->getId() ]);
        }

        return $this->renderForm('variation/new_aside_variation.html.twig', [
            'variation' => $variation,
            'form' => $form,
        ]);






    }





















    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/new/aside/{id}', name: 'app_variation_new_aside', methods: ['GET', 'POST'])]
    public function new_aside( $id,Request $request , AttributRepository $attributRepository ,VariationRepository $variationRepository): Response
    {
        $variation = new Variation();
        $variation->setAttribut($attributRepository->find($id));
        $form = $this->createForm(VariationType::class, $variation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
           

            // $variation_produit = $form->get('produits')->getData();

            // foreach ($variation_produit as $var) {
            //     $variation->addProduit($var);
            // }


            $variationRepository->add($variation, true);
            $this->addFlash('success', 'Variation Ajoute avec succes');

            return $this->redirectToRoute('app_attribut_show', ['id'=> $variation->getAttribut()->getId() ]);
        }

        return $this->renderForm('variation/new_aside.html.twig', [
            'variation' => $variation,
            'form' => $form,
        ]);
    }





    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/new/editproduit/{id}/{slug}', name: 'app_variation_new_variable_edit', methods: ['GET', 'POST'])]
    public function new_variable_edit( $id,$slug,Request $request , AttributRepository $attributRepository ,VariationRepository $variationRepository): Response
    {
        
        
        $variation = new Variation();
        $variation->setAttribut($attributRepository->find($id));
        $form = $this->createForm(VariationType::class, $variation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->addFlash('variation', 'Variation Ajoute avec succes');

            $variation_produit = $form->get('produits')->getData();

            foreach ($variation_produit as $var) {
                $variation->addProduit($var);
            }


            $variationRepository->add($variation, true);

            return $this->redirectToRoute('app_variation_new_variable', ['id'=> $variation->getAttribut()->getId() ]);
        }

        return $this->renderForm('variation/new_editproduit.html.twig', [
            'variation' => $variation,
            'form' => $form,
            'slug' => $slug
        ]);
    }






    



    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/new/{id}', name: 'app_variation_new_variable', methods: ['GET', 'POST'])]
    public function new_variable( $id,Request $request , AttributRepository $attributRepository ,VariationRepository $variationRepository): Response
    {
        
        
        $variation = new Variation();
        $variation->setAttribut($attributRepository->find($id));
        $form = $this->createForm(VariationType::class, $variation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->addFlash('variation', 'Variation Ajoute avec succes');

            $variation_produit = $form->get('produits')->getData();

            foreach ($variation_produit as $var) {
                $variation->addProduit($var);
            }


            $variationRepository->add($variation, true);

            return $this->redirectToRoute('app_variation_new_variable', ['id'=> $variation->getAttribut()->getId() ]);
        }

        return $this->renderForm('variation/new.html.twig', [
            'variation' => $variation,
            'form' => $form,
        ]);
    }















    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/new/variation/{id}', name: 'app_variation_new', methods: ['GET', 'POST'])]
    public function new( Request $request ,Attribut $attribut, VariationRepository $variationRepository): Response
    {
        
        // dd("ff");
       
        $variation = new Variation();
        $form = $this->createForm(VariationType::class, $variation);
        $form->handleRequest($request);
       
        // if(count($form->getErrors())>0){
        //     dd($form->getErrors());
        // }


        if ($form->isSubmitted() && $form->isValid() ) {
            //   dd("flfl");
            $variation->setAttribut($attribut);
            $variation_produit = $form->get('produits')->getData();

            foreach ($variation_produit as $var) {
                $variation->addProduit($var);
            }

            // dd('fjfj');
            $variationRepository->add($variation, true);
            $this->addFlash('success', 'variation ajoute avec succes');
            return $this->redirectToRoute('app_variation_new', ['id' => $attribut->getId() ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('variation/new.html.twig', [
            'variation' => $variation,
            'form' => $form,
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/{id}', name: 'app_variation_show', methods: ['GET'])]
    public function show(Variation $variation): Response
    {
        return $this->render('variation/show.html.twig', [
            'variation' => $variation,
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/{id}/edit', name: 'app_variation_edit', methods: ['GET', 'POST'])]
    public function edit(Variation $variation,Request $request, VariationRepository $variationRepository): Response
    {
        // $variation=$variationRepository->find($id);
        $form = $this->createForm(VariationType::class, $variation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            // $variation_produit = $form->get('produits')->getData();

            // foreach ($variation_produit as $var) {
            //     $variation->addProduit($var);
            // }
            $variationRepository->add($variation, true);
            $this->addFlash('success', 'Variation modifie avec succes');

            return $this->redirectToRoute('app_variation_edit', [ 'id' => $variation->getId()  ]);
        }

        return $this->renderForm('variation/edit.html.twig', [
            'variation' => $variation,
            'form' => $form,
            'attribut' => $variation->getAttribut()
        ]);
    }

    // #[Security("is_granted('ROLE_ADMIN')")]
    // #[Route('/{id}/{slug}', name: 'app_variation_delete', methods: ['POST'])]
    // public function delete($id,$slug,Request $request, VariationRepository $variationRepository): Response
    // {
    //     $variation=$variationRepository->find($id);
        
    //     if ($this->isCsrfTokenValid('delete' . $variation->getId(), $request->request->get('_token'))) {
    //         $variationRepository->remove($variation, true);
    //     }
    //     $this->addFlash('suppression', 'Variation supprime avec succes');
    //     return $this->redirectToRoute('app_attribut_show', [ 'id' => $slug  ]);
    // }

    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/{id}/{slug}', name: 'app_variation_delete_get', methods: ['GET'])]
    public function deleteget($id,$slug,Request $request, VariationRepository $variationRepository): Response
    {
        $variation=$variationRepository->find($id);
        
        // if ($this->isCsrfTokenValid('delete' . $variation->getId(), $request->request->get('_token'))) {
            $variationRepository->remove($variation, true);
        
         $this->addFlash('suppression', 'Variation supprime avec succes');
        return $this->redirectToRoute('app_attribut_show', [ 'id' => $slug  ]);
    }

    #[Route('/delete/group', name: 'app_variation_delete_group', methods: ['POST'])]
    public function deletegroup(Request $request,VariationRepository $variationRepository): Response
    {
        
        // dd($request->get('check1'));
        $array=[];
        foreach($variationRepository->findAll() as $variation){
          if($request->get('check'.$variation->getId())!=null){
           
             array_push($array,$variation->getId());
          }

        }
        foreach($array as $variation){
            $variationRepository->remove($variationRepository->find($variation),true);
        }
        // if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // $userRepository->remove($user, true);
            $this->addFlash('suppression', 'La suppression est effectue  avec succes');

        return $this->redirectToRoute('app_attribut_show', ['id'=> $request->get('attribut') ], Response::HTTP_SEE_OTHER);
    }

}