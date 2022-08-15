<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use App\Form\SousCategorieType;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Security("is_granted('ROLE_ADMIN')")]
#[Route('/admin/sous/categorie')]
class SousCategorieController extends AbstractController
{
    #[Route('/', name: 'app_sous_categorie_index', methods: ['GET'])]
    public function index(SousCategorieRepository $sousCategorieRepository): Response
    {
        return $this->render('sous_categorie/index.html.twig', [
            'sous_categories' => $sousCategorieRepository->findAll(),
        ]);
    }




    #[Route('/corbeille', name: 'app_sous_categorie_corbeille', methods: ['GET'])]
    public function corbeille(SousCategorieRepository $sousCategorieRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        
        $sous_categories=$sousCategorieRepository->findcorbeille();
        

        return $this->render('sous_categorie/corbeille.html.twig', [
            'sous_categories' => $sous_categories
        ]);
    }

    #[Route('/restore/{id}', name: 'app_sous_categorie_restore', methods: ['GET'])]
    public function restore($id,SousCategorieRepository $sousCategorieRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        $sous_categorie=$sousCategorieRepository->find($id);
        $sous_categorie->setDeletedAt(Null);
        $em->persist($sous_categorie);
        $em->flush();        
        $sous_categories=$sousCategorieRepository->findcorbeille();
        return $this->render('sous_categorie/corbeille.html.twig', [
            'sous_categories' => $sous_categories
        ]);
    }

    #[Route('/delete_from_corbeille/{id}', name: 'app_sous_categorie_delete_fromcorbeille', methods: ['GET'])]
    public function deleteforce($id,SousCategorieRepository $sousCategorieRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
       
        //  $em->getFilters()->disable('softdeleteable');
        //  $user=$userRepository->find($id);
         $sousCategorieRepository->deletefromtrash($id);
             
        $sous_categories=$sousCategorieRepository->findcorbeille();
        return $this->render('sous_categorie/corbeille.html.twig', [
            'sous_categories' => $sous_categories
        ]);
    }





    #[Route('/new/{id}', name: 'app_sous_categorie_new_aside', methods: ['GET', 'POST'])]
    public function new_aside($id,Request $request ,CategorieRepository $categorieRepository ,SousCategorieRepository $sousCategorieRepository): Response
    {
        $sousCategorie = new SousCategorie();
        $sousCategorie->setCategorie($categorieRepository->find($id));
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Sous Categorie Ajoute avec succes');
            $sousCategorieRepository->add($sousCategorie, true);

            return $this->redirectToRoute('app_sous_categorie_new_aside', ['id' => $id]);
        }

        return $this->renderForm('sous_categorie/new_aside.html.twig', [
            'sous_categorie' => $sousCategorie,
            'form' => $form,
        ]);
    }








    #[Route('/new/{id}', name: 'app_sous_categorie_new_variable', methods: ['GET', 'POST'])]
    public function new_variable($id,Request $request ,CategorieRepository $categorieRepository ,SousCategorieRepository $sousCategorieRepository): Response
    {
        $sousCategorie = new SousCategorie();
        $sousCategorie->setCategorie($categorieRepository->find($id));
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Sous Categorie Ajoute avec succes');
            $sousCategorieRepository->add($sousCategorie, true);

            return $this->redirectToRoute('app_sous_categorie_new_variable', ['id' => $id]);
        }

        return $this->renderForm('sous_categorie/new.html.twig', [
            'sous_categorie' => $sousCategorie,
            'form' => $form,
        ]);
    }

    #[Route('/new/souscategorie', name: 'app_sous_categorie_new_produit', methods: ['GET', 'POST'])]
    public function newsouscategorie(Request $request, SousCategorieRepository $sousCategorieRepository): Response
    {
        $sousCategorie = new SousCategorie();
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Sous Categorie Ajoute avec succes');


            $sousCategorieRepository->add($sousCategorie, true);

            return $this->redirectToRoute('app_sous_categorie_new_produit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sous_categorie/_form_variable.html.twig', [
            'sous_categorie' => $sousCategorie,
            'form' => $form,
        ]);
    }







    #[Route('/new', name: 'app_sous_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SousCategorieRepository $sousCategorieRepository): Response
    {
        $sousCategorie = new SousCategorie();
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $sousCategorieRepository->add($sousCategorie, true);

            return $this->redirectToRoute('app_sous_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sous_categorie/new.html.twig', [
            'sous_categorie' => $sousCategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sous_categorie_show', methods: ['GET'])]
    public function show(SousCategorie $sousCategorie): Response
    {
        return $this->render('sous_categorie/show.html.twig', [
            'sous_categorie' => $sousCategorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sous_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SousCategorie $sousCategorie, SousCategorieRepository $sousCategorieRepository): Response
    {
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $sousCategorieRepository->add($sousCategorie, true);

            return $this->redirectToRoute('app_sous_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sous_categorie/edit.html.twig', [
            'sous_categorie' => $sousCategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sous_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, SousCategorie $sousCategorie, SousCategorieRepository $sousCategorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sousCategorie->getId(), $request->request->get('_token'))) {
            $sousCategorieRepository->remove($sousCategorie, true);
        }

        return $this->redirectToRoute('app_sous_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}