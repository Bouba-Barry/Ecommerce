<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// #[Route('/admin')]
class HomeController extends AbstractController
{
    #[Route('/admin', name: 'app_home_admin')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
    #[Route('', name: 'app_home')]
    public function home(ProduitRepository $produitRepository): Response
    {
        return $this->render('home.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

}
