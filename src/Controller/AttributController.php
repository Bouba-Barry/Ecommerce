<?php

namespace App\Controller;

use App\Entity\Attribut;
use App\Form\AttributType;
use App\Repository\AttributRepository;
use App\Repository\ProduitRepository;
use App\Repository\VariationRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/attribut')]
#[Security("is_granted('ROLE_ADMIN')")]
class AttributController extends AbstractController
{

    #[Route('/', name: 'app_attribut_index', methods: ['GET'])]
    public function index(AttributRepository $attributRepository): Response
    {

        return $this->render('attribut/index.html.twig', [
            'attributs' => $attributRepository->findAll(),
        ]);
    }

    #[Route('/aside', name: 'app_attribut_index_aside', methods: ['GET'])]
    public function indexaside(AttributRepository $attributRepository): Response
    {

        return $this->render('attribut/index_aside.html.twig', [
            'attributs' => $attributRepository->findAll(),
        ]);
    }


    #[Route('/getvariations/{id}', name: 'app_attribut_variations', methods: ['GET'])]
    public function getVariations(Attribut $attribut,  SerializerInterface $serializer ,AttributRepository $attributRepository): JsonResponse
    {

  
        $variations=$serializer->serialize($attribut->getVariations(), 'json', ['groups' => ['attribut']]);
        $obj=json_decode($variations);
        return $this->json($obj);
    }



    #[Route('/corbeille', name: 'app_attribut_corbeille', methods: ['GET'])]
    public function corbeille(AttributRepository $attributRepository ,ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        
        $attributs=$attributRepository->findcorbeille();
        

        return $this->render('attribut/corbeille.html.twig', [
            'attributs' => $attributs
        ]);
    }

    #[Route('/restore/{id}', name: 'app_attribut_restore', methods: ['GET'])]
    public function restore($id,AttributRepository $attributRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        $attribut=$attributRepository->find($id);
        $attribut->setDeletedAt(Null);
        $em->persist($attribut);
        foreach($attribut->getVariations() as $variation){
            $variation->setDeletedAt(Null);
            $em->persist($variation);
        }

        $em->flush();        
        $attributs=$attributRepository->findcorbeille();
        $this->addFlash('success', 'Attribut restaurer avec succes');
        return $this->render('attribut/corbeille.html.twig', [
            'attributs' => $attributs
        ]);
    }

    #[Route('/delete_from_corbeille/{id}', name: 'app_attribut_delete_fromcorbeille', methods: ['GET'])]
    public function deleteforce($id,AttributRepository $attributRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
       
        //  $em->getFilters()->disable('softdeleteable');
        //  $user=$userRepository->find($id);
         $attributRepository->deletefromtrash($id);
             
        $attributs=$attributRepository->findcorbeille();
        $this->addFlash('suppression', 'Attribut supprime definitivement avec succes');
        return $this->render('attribut/corbeille.html.twig', [
            'attributs' => $attributs
        ]);
    }





    #[Route('/new/aside', name: 'app_attribut_new_aside', methods: ['GET', 'POST'])]
    public function newaside(Request $request, AttributRepository $attributRepository): Response
    {
        $attribut = new Attribut();
        $form = $this->createForm(AttributType::class, $attribut);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $attributRepository->add($attribut, true);
            $this->addFlash('success', 'Attribut ajoute avec succes vous pouvez ajoute des Variations');

            return $this->redirectToRoute('app_attribut_show', ['id' => $attribut->getId() ]);
        }

        return $this->renderForm('attribut/new_aside.html.twig', [
            'attribut' => $attribut,
            'form' => $form,
        ]);
    }



    #[Route('/new', name: 'app_attribut_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AttributRepository $attributRepository): Response
    {
        $attribut = new Attribut();
        $form = $this->createForm(AttributType::class, $attribut);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $attributRepository->add($attribut, true);
            $this->addFlash('success', 'Attribut ajoute avec succes vous pouvez ajoute des Variations');

            return $this->redirectToRoute('app_attribut_new');
        }

        return $this->renderForm('attribut/new.html.twig', [
            'attribut' => $attribut,
            'form' => $form,
        ]);
    }





    #[Route('/new/editproduit/{id}', name: 'app_attribut_new_editproduit', methods: ['GET', 'POST'])]
    public function neweditproduit($id,Request $request,ProduitRepository $produitRepository ,AttributRepository $attributRepository): Response
    {
        $attribut = new Attribut();
        $form = $this->createForm(AttributType::class, $attribut);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $attributRepository->add($attribut, true);

            return $this->redirectToRoute('app_variation_new_variable_edit', ['id' => $attribut->getId(),'slug'=>$id  ]);
        }

        return $this->renderForm('attribut/new_editproduit.html.twig', [
            'attribut' => $attribut,
            'form' => $form,
            'produit' => $produitRepository->find($id)
        ]);
    }

    #[Route('/{id}', name: 'app_attribut_show', methods: ['GET'])]
    public function show(Attribut $attribut): Response
    {
        return $this->render('attribut/show.html.twig', [
            'attribut' => $attribut,
            'variations' =>$attribut->getVariations()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_attribut_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Attribut $attribut, AttributRepository $attributRepository): Response
    {
       
        $form = $this->createForm(AttributType::class, $attribut);
        $form->handleRequest($request);
        if (count($form->getErrors()) > 0) {
                 dd($form->getErrors());
             }

        if ($form->isSubmitted() && $form->isValid()) {
            
            

            $attributRepository->add($attribut, true);

            $this->addFlash('success', 'Attribut modifie avec succes');

            return $this->redirectToRoute('app_attribut_edit', ['id'=>$attribut->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('attribut/edit.html.twig', [
            'attribut' => $attribut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attribut_delete', methods: ['POST'])]
    public function delete(Request $request, Attribut $attribut,VariationRepository $variationRepository ,AttributRepository $attributRepository): Response
    {
        
        if ($this->isCsrfTokenValid('delete' . $attribut->getId(), $request->request->get('_token'))) {
            foreach($attribut->getVariations() as $variation){
             $variationRepository->remove($variation,true);
            }
            $attributRepository->remove($attribut, true);
        }

        $this->addFlash('suppression', 'Attribut supprime avec succes');

        return $this->redirectToRoute('app_attribut_index_aside', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{id}', name: 'app_attribut_delete_get', methods: ['GET'])]
    public function deleteget(Request $request, Attribut $attribut,VariationRepository $variationRepository ,AttributRepository $attributRepository): Response
    {
        
        // if ($this->isCsrfTokenValid('delete' . $attribut->getId(), $request->request->get('_token'))) {
            foreach($attribut->getVariations() as $variation){
                $variationRepository->remove($variation,true);
               }
            $attributRepository->remove($attribut, true);
        

        $this->addFlash('suppression', 'Attribut supprime avec succes');

        return $this->redirectToRoute('app_attribut_index_aside', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/delete/group', name: 'app_attribut_delete_group', methods: ['POST'])]
    public function deletegroup(Request $request,VariationRepository $variationRepository,AttributRepository $attributRepository): Response
    {
        
        // dd($request->get('check1'));
        $array=[];
        foreach($attributRepository->findAll() as $attribut){
          if($request->get('check'.$attribut->getId())!=null){
           
             array_push($array,$attribut->getId());
          }

        }
        foreach($array as $attribut){
            foreach($attributRepository->find($attribut)->getVariations() as $variation){
                $variationRepository->remove($variation,true);
               }
            $attributRepository->remove($attributRepository->find($attribut),true);
        }
        // if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // $userRepository->remove($user, true);
            $this->addFlash('suppression', 'La suppression est effectue  avec succes');

        return $this->redirectToRoute('app_attribut_index_aside', [], Response::HTTP_SEE_OTHER);
    }


}