<?php

namespace App\Controller;

use App\Entity\Email;
use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Wishlist;
use App\Form\UserType;
use App\Form\ClientType;
use App\Repository\EmailRepository;
use App\Repository\UserRepository;
use App\Repository\PanierRepository;
use App\Repository\WishlistRepository;
use App\Services\MailerService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/user')]
class ClientController extends AbstractController
{



    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine, WishlistRepository $wishlistRepository, PanierRepository $panierRepository, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {

        $user = new User();

        $form = $this->createForm(ClientType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);



            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );


            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);

            $userRepository->add($user, true);

            $panier = new Panier();
            $wish = new Wishlist();
            $panier->setUser($user);
            $panierRepository->add($panier, true);
            $wish->setUser($user);
            $wishlistRepository->add($wish, true);


            return $this->redirectToRoute('app_user_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('frontend/register.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // #[Security("is_granted('ROLE_USER')")]
    #[Route('/profile', name: 'app_client_index')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine, UserRepository $userRepository): Response
    {


        $userlogged = $this->getUser();
        if (in_array("ROLE_USER", $userlogged->getRoles())) {
            $user = $userRepository->find($userlogged);
            $form = $this->createForm(ClientType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {



                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
                $user->setPassword($hashedPassword);
                $userRepository->add($user, true);
                return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
            }

            // if ($userlogged == null) {
            //     return $this->redirectToRoute('app_user_login', [], Response::HTTP_SEE_OTHER);
            // }


            // dd($userlogged->getRoles());
            // dd($userlogged->Id);

            $order = $user->getCommandes();
            $wishlists = $user->getWishlists();

            // dd($order);
            return $this->renderForm('frontend/profile.html.twig', [
                'user' => $userlogged,
                'orders' => $order,
                'form' => $form,
                'wishlists' => $wishlists
            ]);
        } else {
            return $this->redirectToRoute('app_user_login', [], Response::HTTP_SEE_OTHER);
        }
    }

    // #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(ClientType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('client/edit.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function feedbackUser(Request $request, EmailRepository $emailRepository): Response
    {
        if ($request->isMethod('POST')) {

            $name = $request->get('name');
            $email_emeteur = $request->get('email');
            $subject = $request->get('msg_subject');
            $msg = $request->get('message');
            $pattern = '/([a-zA-Z0-9]+)([\.{1}])?([a-zA-Z0-9]+)\@gmail([\.])com/';
            $to = 'boubatest1@gmail.com';

            if (preg_match($pattern, $email_emeteur) === false) {

                $this->addFlash('mail_error', 'Votre email est invalide, Veuillez Réessayer ');

                return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);
            } else {
                $email = new Email();
                $email->setNom($name);
                $email->setMailEmeteur($email_emeteur);
                $email->setTitre($subject);
                $email->setContent($msg);
                $emailRepository->add($email, true);

                $this->addFlash('success', 'Merci de nous avoir contactez, Nous vous férrons un retour le plustôt possible ');

                return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('frontend/contact.html.twig', []);
    }
}