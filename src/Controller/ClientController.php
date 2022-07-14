<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Security("is_granted('ROLE_USER')")]
#[Route('/client', name: 'app_client')]
class ClientController extends AbstractController
{


    #[Route('/', name: 'app_client')]
    public function index(ManagerRegistry $doctrine): Response
    {

        // $userlogged = $this->getUser();
        // $user = new User();
        // $id = $userlogged->id;

        // $entityManager = $doctrine->getManager();

        // $user = $entityManager->getRepository(User::class)->find($id);

        // // var_dump($userlogged);
        // $commandes = $user->getCommandes();

        return $this->render('client/index.html.twig', [
            // 'controller_name' => $user,
        ]);
    }
}