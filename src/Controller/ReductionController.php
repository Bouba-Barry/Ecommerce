<?php

namespace App\Controller;

use App\Entity\Reduction;
use App\Form\ReductionType;
use App\Repository\QuantiteRepository;
use App\Repository\ReductionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Security("is_granted('ROLE_ADMIN')")]
#[Route('/admin/reduction')]
class ReductionController extends AbstractController
{
    #[Route('/', name: 'app_reduction_index', methods: ['GET'])]
    public function index(ReductionRepository $reductionRepository): Response
    {
        return $this->render('reduction/index.html.twig', [
            'reductions' => $reductionRepository->findAll(),
        ]);
    }



    #[Route('/corbeille', name: 'app_reduction_corbeille', methods: ['GET'])]
    public function corbeille(ReductionRepository $reductionRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        
        $reductions=$reductionRepository->findcorbeille();
        

        return $this->render('reduction/corbeille.html.twig', [
            'reductions' => $reductions
        ]);
    }

    #[Route('/restore/{id}', name: 'app_reduction_restore', methods: ['GET'])]
    public function restore($id,ReductionRepository $reductionRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        $reduction=$reductionRepository->find($id);
        $reduction->setDeletedAt(Null);
        $em->persist($reduction);
        $em->flush();        
        $reductions=$reductionRepository->findcorbeille();
        return $this->render('reduction/corbeille.html.twig', [
            'reductions' => $reductions
        ]);
    }

    #[Route('/delete_from_corbeille/{id}', name: 'app_reduction_delete_fromcorbeille', methods: ['GET'])]
    public function deleteforce($id,ReductionRepository $reductionRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
       
        //  $em->getFilters()->disable('softdeleteable');
        //  $user=$userRepository->find($id);
         $reductionRepository->deletefromtrash($id);
             
        $reductions=$reductionRepository->findcorbeille();
        return $this->render('reduction/corbeille.html.twig', [
            'reductions' => $reductions
        ]);
    }











    #[Route('/new', name: 'app_reduction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine,ReductionRepository $reductionRepository,QuantiteRepository $quantiteRepository): Response
    {
        $reduction = new Reduction();
        $form = $this->createForm(ReductionType::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $entityManager = $doctrine->getManager();

            $produit = $form->get('produits')->getData();

            foreach ($produit as $var) {
                $prix = str_replace("%", "", $reduction->getPourcentage());
                if($var->gettype()=="variable"){
                  foreach( $var->getQuantites()  as $quantite){
                     $quantite->setPrix($quantite->getPrix()-($prix * $quantite->getPrix() / 100 ));
                    //  $entityManager->persist($quantite);
                    //  $entityManager->flush();
                    // dd($quantite->getPrix());
                    $var->addQuantite($quantite);
                  }
                }
                else{
                $var->setNouveauPrix($var->getAncienPrix() - ($prix * $var->getAncienPrix() / 100));
                }
                $reduction->addProduit($var);
                
            }

            $reductionRepository->add($reduction, true);
            $this->addFlash('success', 'Reduction ajoute avec succes');

            return $this->redirectToRoute('app_reduction_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reduction/new.html.twig', [
            'reduction' => $reduction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reduction_show', methods: ['GET'])]
    public function show(Reduction $reduction): Response
    {
        return $this->render('reduction/show.html.twig', [
            'reduction' => $reduction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reduction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reduction $reduction, ReductionRepository $reductionRepository): Response
    {
        $form = $this->createForm(ReductionType::class, $reduction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $produit = $form->get('produits')->getData();

            foreach ($produit as $var) {
                $prix = str_replace("%", "", $reduction->getPourcentage());
                $var->setNouveauPrix($var->getAncienPrix() - ($prix * $var->getAncienPrix() / 100));
                $reduction->addProduit($var);
            }

            $reductionRepository->add($reduction, true);
            $this->addFlash('success', 'Reduction modifie avec succes');
            return $this->redirectToRoute('app_reduction_edit', ['id' => $reduction->getId() ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reduction/edit.html.twig', [
            'reduction' => $reduction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reduction_delete', methods: ['POST'])]
    public function delete(Request $request, Reduction $reduction, ReductionRepository $reductionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reduction->getId(), $request->request->get('_token'))) {
            $reductionRepository->remove($reduction, true);
        }

        return $this->redirectToRoute('app_reduction_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/delete/{id}', name: 'app_reduction_delete_get', methods: ['GET'])]
    public function deleteget(Request $request, Reduction $reduction, ReductionRepository $reductionRepository): Response
    {
        // if ($this->isCsrfTokenValid('delete' . $reduction->getId(), $request->request->get('_token'))) {
            $reductionRepository->remove($reduction, true);
            $this->addFlash('suppression', 'Reduction supprime avec succes');

        return $this->redirectToRoute('app_reduction_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/delete/group', name: 'app_reduction_delete_group', methods: ['POST'])]
    public function deletegroup(Request $request,ReductionRepository $reductionRepository): Response
    {
        // dd($request->get('check1'));
        $array=[];
        foreach($reductionRepository->findAll() as $reduction){
          if($request->get('check'.$reduction->getId())!=null){
           
             array_push($array,$reduction->getId());
          }

        }
        foreach($array as $reduction){
            $reductionRepository->remove($reductionRepository->find($reduction),true);
        }
        // if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // $userRepository->remove($user, true);
            $this->addFlash('suppression', 'La suppression est effectue  avec succes');

        return $this->redirectToRoute('app_reduction_index', [], Response::HTTP_SEE_OTHER);
    }


}