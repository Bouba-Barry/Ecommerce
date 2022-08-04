<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\User;
use App\Entity\Wishlist;
use App\Repository\UserRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\WishlistRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WishController extends AbstractController
{
    #[Route('/wish/{id}', name: 'app_wish')]
    public function index(User $user,SerializerInterface $serializer, WishlistRepository $wishlistRepository): Response
    {

        $wishlist=$user->getWishlists();
        $json = $serializer->serialize($wishlist, 'json', ['groups' => ['prod:read']]);
        $json=json_decode($json);
        
        $wish=$wishlistRepository->find($json[0]->id);
        $wishproducts=$wish->getProduit();
        $json = $serializer->serialize($wishproducts, 'json', ['groups' => ['prod:read']]);
        
        return $this->render('wish/index.html.twig', [
            'produits' => $wishproducts
        ]);

    }
    #[Route('/wish/{id}/{user_id}', name: 'app_wish_add', methods: ['GET'])]
    public function wish_add($id, $user_id, WishlistRepository $wishlistRepository ,UserRepository $userRepository, ManagerRegistry $doctrine, ProduitRepository $produitRepository, PanierRepository $panierRepository) : Response
    {
         $wish=$wishlistRepository->findOneBy(['user' => $userRepository->find($user_id) ]);
         $wish->addProduit($produitRepository->find($id));
  
         $entityManager = $doctrine->getManager();
         $entityManager->persist($wish);
         $entityManager->flush();

         return $this->redirectToRoute('app_panier_infos', ['id' => $user_id], Response::HTTP_SEE_OTHER);

      

    }

    // wish_delete/${vals}
    #[Route('/wish_delete/{id}/{user_id}', name: 'app_wish_delete', methods: ['GET'])]
    public function wish_delete($id, $user_id, WishlistRepository $wishlistRepository ,UserRepository $userRepository, ManagerRegistry $doctrine, ProduitRepository $produitRepository, PanierRepository $panierRepository) : Response
    {
         $wish=$wishlistRepository->findOneBy(["user" => $userRepository->find($user_id) ]);
         $wish->removeProduit($produitRepository->find($id));
         $entityManager = $doctrine->getManager();
         $entityManager->flush();

         return $this->redirectToRoute('app_panier_infos', ['id' => $user_id], Response::HTTP_SEE_OTHER);

         
    }

   
    #[Route('/wish_check/{id}/{id_user}', name: 'app_wish_check', methods: ['GET'])]
    public function check_wish($id, $id_user,SerializerInterface $serializer,ProduitRepository $produitRepository ,UserRepository $userRepository, wishlistRepository $wishlistRepository, ManagerRegistry $doctrine): JsonResponse
    {

        $wish = $wishlistRepository->findOneBy(['user' => $userRepository->find($id_user)]);
        $json = $serializer->serialize($wish->getProduit(), 'json', ['groups' => ['prod:read']]);
         $produit=$produitRepository->find($id);
        
        $obj=json_decode($json);
        $exist=0;
        foreach($obj as $array){
            if($array->id== $produit->getId() ){
             $exist=1;
             break; 
            }
            else
            continue;
        }
        
        if ($exist==1) {
            return $this->json(1);
        } else {
            return $this->json(0);
        }



    }




}
