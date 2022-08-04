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

#[Route('/feedback')]
class FeadBackController extends AbstractController
{
    // #[Route('/', name: 'app_fead_back_index', methods: ['GET'])]
    // public function index(FeadBackRepository $feadBackRepository): Response
    // {
    //     return $this->render('fead_back/index.html.twig', [
    //         'fead_backs' => $feadBackRepository->findAll(),
    //     ]);
    // }

    #[Route('/product', name: 'app_prod_feedback')]
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

    #[Route('/new', name: 'app_client_feedback', methods: ['GET', 'POST'])]
    public function new(Request $request, FeadBackRepository $feadBackRepository, ProduitRepository $produitRepository): Response
    {
        $feadBack = new FeadBack();
        $form = $this->createForm(FeadBackType::class, $feadBack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $feadBack->setUser($this->getUser());

            $feadBackRepository->add($feadBack, true);

            return $this->redirectToRoute('app_client_feedback', [], Response::HTTP_SEE_OTHER);
        }



        return $this->renderForm('frontend/feedback.html.twig', [
            // 'fead_back' => $feadBack,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_fead_back_show', methods: ['GET'])]
    // public function show(FeadBack $feadBack): Response
    // {
    //     return $this->render('fead_back/show.html.twig', [
    //         'fead_back' => $feadBack,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_fead_back_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, FeadBack $feadBack, FeadBackRepository $feadBackRepository): Response
    // {
    //     $form = $this->createForm(FeadBackType::class, $feadBack);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $feadBackRepository->add($feadBack, true);

    //         return $this->redirectToRoute('app_fead_back_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('fead_back/edit.html.twig', [
    //         'fead_back' => $feadBack,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_fead_back_delete', methods: ['POST'])]
    public function delete(Request $request, FeadBack $feadBack, FeadBackRepository $feadBackRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $feadBack->getId(), $request->request->get('_token'))) {
            $feadBackRepository->remove($feadBack, true);
        }

        return $this->redirectToRoute('app_fead_back_index', [], Response::HTTP_SEE_OTHER);
    }
}