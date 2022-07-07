<?php

namespace App\Controller;

use App\Entity\Variation;
use App\Form\VariationType;
use App\Repository\VariationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Security("is_granted('ROLE_ADMIN')")]
#[Route('/variation')]
class VariationController extends AbstractController
{
    #[Route('/', name: 'app_variation_index', methods: ['GET'])]
    public function index(VariationRepository $variationRepository): Response
    {
        return $this->render('variation/index.html.twig', [
            'variations' => $variationRepository->findAll(),
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/new', name: 'app_variation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VariationRepository $variationRepository): Response
    {
        $variation = new Variation();
        $form = $this->createForm(VariationType::class, $variation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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

            $variation_produit = $form->get('produits')->getData();

            foreach ($variation_produit as $var) {
                $variation->addProduit($var);
            }
            $variationRepository->add($variation, true);

            return $this->redirectToRoute('app_variation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('variation/edit.html.twig', [
            'variation' => $variation,
            'form' => $form,
        ]);
    }

    #[Security("is_granted('ROLE_ADMIN')")]
    #[Route('/{id}', name: 'app_variation_delete', methods: ['POST'])]
    public function delete(Request $request, Variation $variation, VariationRepository $variationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $variation->getId(), $request->request->get('_token'))) {
            $variationRepository->remove($variation, true);
        }

        return $this->redirectToRoute('app_variation_index', [], Response::HTTP_SEE_OTHER);
    }
}