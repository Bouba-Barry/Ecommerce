<?php

namespace App\Controller;

use App\Entity\FeadBack;
use App\Form\FeadBackType;
use App\Repository\FeadBackRepository;
use App\Repository\ProduitRepository;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class FeadBackController extends AbstractController
{
    #[Route('/admin/feedback', name: 'app_fead_back_index', methods: ['GET'])]
    public function index(FeadBackRepository $feadBackRepository): Response
    {
        $fead = $feadBackRepository->findBy(['produit' => null]);
        // dd($fead);
        return $this->render('fead_back/index.html.twig', [
            'fead_backs' => $fead,
        ]);
    }

    #[Route('/admin/feedback/prod', name: 'app_fead_prod', methods: ['GET'])]
    public function feed_back_prod(FeadBackRepository $feadBackRepository, ProduitRepository $prod): Response
    {
        $fead = $feadBackRepository->findFeedProd();
        // dd($fead->produit);
        // dd($fead);
        $fead = $prod->findFeedProd();
        // dd($fead);
        return $this->render('fead_back/prod.html.twig', [
            'feed_backs' => $fead,
        ]);
    }

    #[Route('/feedback/product', name: 'app_prod_feedback')]
    public function feedbackProd(Request $request, FeadBackRepository $feadBackRepository, ProduitRepository $produitRepository): JsonResponse
    {
        if ($request->get('name') && $request->get('body') && $request->get('title')) {
            $name = $request->get('name');
            $subject = $request->get('title');
            $msg = $request->get('body');
            $prod = $request->get('produit');

            if (is_string($name) && is_string($subject) && is_string($msg)) {
                if (strlen($name) > 0 && strlen($subject) && strlen($msg)) {
                    $review = new FeadBack();
                    $review->setPseudo($name);
                    $review->setContent($msg);
                    $review->setTitre($subject);
                    $review->setUser($this->getUser());
                    $review->setProduit($produitRepository->findOneBy(['id' => $prod]));
                    $feadBackRepository->add($review, true);
                }
                $myObj = new stdClass();
                $myObj->msg = "Votre Avis a ete pris en compte";
                // $json = {'msg': 'ajout avec success'};
                $json = json_encode($myObj);
                return $this->json($json);
            } else {
                $obj = new stdClass();
                $obj->msg = "verifiez vos saisie";

                $erreur = json_encode($obj);
                return $this->json($erreur);
            }
        }
    }

    #[Route('/feedback/new', name: 'app_client_feedback', methods: ['GET', 'POST'])]
    public function new(Request $request, FeadBackRepository $feadBackRepository, ProduitRepository $produitRepository): Response
    {
        $feadBack = new FeadBack();
        $form = $this->createForm(FeadBackType::class, $feadBack);
        $form->handleRequest($request);
        $sujet = $request->get('msg_subject');
        if ($form->isSubmitted() && $form->isValid()) {

            $feadBack->setUser($this->getUser());
            $feadBack->setTitre($sujet);

            $feadBackRepository->add($feadBack, true);
            $this->addFlash('success', 'Votre avis a été pris en compte avec succès');
            return $this->redirectToRoute('app_client_feedback', [], Response::HTTP_SEE_OTHER);
        }



        return $this->renderForm('frontend/feedback.html.twig', [
            // 'fead_back' => $feadBack,
            'form' => $form,
        ]);
    }

    #[Route('/admin/feedback/{id}', name: 'app_fead_back_show', methods: ['GET'])]
    public function show(FeadBack $feadBack, Request $request): Response
    {
        return $this->render('fead_back/show.html.twig', [
            'feed_back' => $feadBack,
        ]);
    }



    #[Route('/admin/feedback/{id}', name: 'app_fead_back_delete_get', methods: ['POST', 'GET'])]
    public function delete(Request $request, FeadBack $feadBack, FeadBackRepository $feadBackRepository): Response
    {
        $feadBackRepository->remove($feadBack, true);
        $this->addFlash('suppression', 'le feedback a été supprimer avec succès');

        return $this->redirectToRoute('app_fead_back_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/admin/feedback/delete/group', name: 'app_feedBack_delete_group', methods: ['POST'])]
    public function deletegroup(Request $request, FeadBackRepository $feadBackRepository): Response
    {
        // dd($request->get('check1'));
        $array = [];
        foreach ($feadBackRepository->findAll() as $feed) {
            if ($request->get('check' . $feed->getId()) != null) {

                array_push($array, $feed->getId());
            }
        }
        foreach ($array as $cmd) {
            $feadBackRepository->remove($feadBackRepository->find($cmd), true);
        }

        $this->addFlash('suppression', 'suppression  effectuée  avec succes');

        return $this->redirectToRoute('app_fead_back_index', [], Response::HTTP_SEE_OTHER);
    }
}