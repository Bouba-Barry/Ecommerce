<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

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

    #[Route('/panier/{id}', name: 'app_panier',methods: ['GET'])]
    public function panier($id,ProduitRepository $produitRepository): Response
    {
        
        // $val->array_push($produit->getId());
        $d=str_split($id);
        $array_ids=[];
        for($i=0;$i<count($d);$i++){
          if($d[$i]!==",")
          array_push($array_ids,$d[$i]);
        }
      
        return $this->render('panier.html.twig', [
            'produits' => $produitRepository->findBy(['id'=>$array_ids]),
        ]);
        
    }

}
