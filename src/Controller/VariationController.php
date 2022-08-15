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
            $this->addFlash('variation', 'Variation Ajoute avec succes');

            $variation_produit = $form->get('produits')->getData();

            foreach ($variation_produit as $var) {
                $variation->addProduit($var);
            }


            $variationRepository->add($variation, true);

            return $this->redirectToRoute('app_variation_new_aside', ['id'=> $variation->getAttribut()->getId() ]);
        }

        return $this->renderForm('variation/new_aside.html.twig', [
            'variation' => $variation,
            'form' => $form,
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
    #[Route('/new', name: 'app_variation_new', methods: ['GET', 'POST'])]
    public function new( Request $request , VariationRepository $variationRepository): Response
    {
        
       
        $variation = new Variation();
        $form = $this->createForm(VariationType::class, $variation);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $variation_produit = $form->get('produits')->getData();

            foreach ($variation_produit as $var) {
                $variation->addProduit($var);
            }


            $variationRepository->add($variation, true);

            return $this->redirectToRoute('app_variation_index', [], Response::HTTP_SEE_OTHER);
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
    public function edit(Request $request, Variation $variation, VariationRepository $variationRepository): Response
    {
        $form = $this->createForm(VariationType::class, $variation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Variation modifie avec succes');

            $variation_produit = $form->get('produits')->getData();

            foreach ($variation_produit as $var) {
                $variation->addProduit($var);
            }
            $variationRepository->add($variation, true);

            return $this->redirectToRoute('app_variation_index_attribut', [ 'id' => $variation->getAttribut()->getId()  ]);
        }

        return $this->renderForm('variation/edit.html.twig', [
            'variation' => $variation,
            'form' => $form,
            'attribut' => $variation->getAttribut()
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/{id}', name: 'app_variation_delete', methods: ['POST'])]
    public function delete(Request $request, Variation $variation, VariationRepository $variationRepository): Response
    {
        $this->addFlash('suppression', 'Variation supprime avec succes');
        if ($this->isCsrfTokenValid('delete' . $variation->getId(), $request->request->get('_token'))) {
            $variationRepository->remove($variation, true);
        }

        return $this->redirectToRoute('app_variation_index_attribut', [ 'id' => $variation->getAttribut()->getId()  ]);
    }
}