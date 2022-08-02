<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Panier;
use App\Entity\User;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/payment')]
class PaymentController extends AbstractController
{
    // #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    // public function index(CommandeRepository $commandeRepository): Response
    // {
    //     return $this->render('commande/index.html.twig', [
    //         'commandes' => $commandeRepository->findAll(),
    //     ]);
    // }
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/paypal', name: 'app_paypal', methods: ['GET', 'POST'])]
    public function paypal(Request $request, UserRepository $userRepository, CommandeRepository $commandeRepository): Response
    {
        // $commandes = $request->query->get('commande');
        $session = $this->requestStack->getSession();
        $commandes = $session->get('commande');
        $total = $session->get('total');
        // $commande = \json_decode($commandes);
        // dd($foo->getUser());
        // dd($foo);

        $user = $commandes->getUser(); //user lié à la commande
        //  dd($user); // panier de ce user

        $panier = $user->getPaniers();
        // dd($panier);
        return $this->renderForm('payment/paypal.html.twig', [
            'commandes' => $commandes,
            'panier' => $panier,
            'total' => $total
        ]);
    }

    #[Route('/bank', name: 'app_bank', methods: ['GET', 'POST'])]
    public function bank(Request $request, CommandeRepository $commandeRepository)
    {

        // $commandes = $request->query->get('commande');
        $session = $this->requestStack->getSession();
        $commandes = $session->get('commande');
        $total = $session->get('total');


        $user = $commandes->getUser(); //user lié à la commande
        $key =  "sk_test_51LS3Z6B4pa87HJY2RvFzEsuMWP6lDNov4EbzLHNTMJqFE45FRf2609THNAuZbIusETmRUhLUneFiXgOID0Si4mL600t4o4uzur";
        $panier = $user->getPaniers();
        // dd($panier);
        \Stripe\Stripe::setApiKey($key);

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount' => 2000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' =>  $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
        // return $this->renderForm('payment/bank.html.twig', [
        //     'commandes' => $commandes,
        //     'panier' => $panier,
        //     'total' => $total
        // ]);
    }

    #[Route('/facture', name: 'app_fact', methods: ['GET', 'POST'])]
    public function facture(Request $request, UserRepository $userRepository, CommandeRepository $commandeRepository): Response
    {
        $cookie = $request->cookies->get('val');


        $session = $this->requestStack->getSession();
        $commandes = $session->get('commande');

        // $cmd = $commandeRepository->find($commandes);

        if ($this->getUser()) {
            $user = $this->getUser();
            // dd($user->getId());
            // dd($user->getPaniers());
            $user = $userRepository->find($user->getId());
            $panier =  $user->getPaniers();
            foreach ($panier as $p) {
                foreach ($p->getProduit() as $prod) {
                    // dd($prod);
                    $commandes->ajout_prod($commandes->getId(), $prod->getId(), $prod->qte_produit);
                    $p->removeProduit($prod);
                }
            }
            // foreach($panier->getProduit() as $prod){
            //     $panier->Remov
            // }
            // dd($panier['id']);
        }
        // dd($cookie);
        // return $this->json($data);
        return $this->render('payment/facture.html.twig', [
            'detail_facture' => $cookie,
            'commande' => $commandes
        ]);
    }

    #[Route('/cash', name: 'app_cash', methods: ['GET', 'POST'])]
    public function cash(Request $request, CommandeRepository $commandeRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->add($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }
    #[Route('/success-url', name: 'success_url', methods: ['GET', 'POST'])]
    public function successUrl(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    #[Route('/cancel-url', name: 'cancel_url', methods: ['GET', 'POST'])]
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}