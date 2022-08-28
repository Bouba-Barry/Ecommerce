<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Services\PdfService;
use App\Services\CurrencyConvert;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\QuantiteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function paypal(Request $request, PanierRepository $panierRepository, UserRepository $userRepository, CommandeRepository $commandeRepository): Response
    {
        // $commandes = $request->query->get('commande');
        $session = $this->requestStack->getSession();
        $commandes = $session->get('commande');
        $totalDh = $session->get('total');

        $request->cookies->set('total', $totalDh);

        $user = $commandes->getUser(); //user lié à la commande
        //  dd($user); // panier de ce user
        $arrayProd = [];
        if ($this->getUser()) {
            // $user = $userRepository->find($user->getId());
            $panier =  $user->getPaniers();

            foreach ($panier as $p) {
                foreach ($p->getProduit() as $prod) {
                    $total = 0;
                    // foreach($panierRepository->selectPanierProd($panier->))
                    $qte = $panierRepository->getQteProd($p->getId(), $prod->getId());
                    // dd($qte[0]['qte_produit']);
                    // dd($prod);
                    if ($prod->getNouveauPrix() != null) {
                        $total = $qte[0]['qte_produit'] * $prod->getNouveauPrix();
                    } else {
                        $total = $qte[0]['qte_produit'] * $prod->getAncienPrix();
                    }
                    $nom = $prod->getDesignation();
                    $qte = $qte[0]['qte_produit'];
                    $total = $total;
                    $array = [
                        'nom' => $nom,
                        'qte' => $qte,
                        'total' => $total,
                    ];
                    $val = json_encode($array);
                    array_push($arrayProd, $val);
                }
            }
        }
        // dd($arrayProd);
        $currency = new CurrencyConvert();
        $total = round($currency->thmx_currency_convert($totalDh, 'MAD', 'USD'));
        $panier = $user->getPaniers();
        // dd($panier);
        return $this->renderForm('payment/paypal.html.twig', [
            'commandes' => $commandes,
            'panier' => $panier,
            'totalDh' => $totalDh,
            'total' => $total
        ]);
    }

    #[Route('/bank', name: 'app_bank', methods: ['GET', 'POST'])]
    public function bank(Request $request, UserRepository $userRepository, CommandeRepository $commandeRepository): Response
    {
        // $commandes = $request->query->get('commande');
        $session = $this->requestStack->getSession();
        $commandes = $session->get('commande');
        $total = $session->get('total');

        $request->cookies->set('total', $total);

        $user = $commandes->getUser(); //user lié à la commande
        //  dd($user); // panier de ce user

        $panier = $user->getPaniers();
        // dd($panier);
        return $this->renderForm('payment/bank.html.twig', [
            'commandes' => $commandes,
            'panier' => $panier,
            'total' => $total
        ]);
    }

    #[Route('/stripe', name: 'app_stripe', methods: ['GET', 'POST'])]
    public function stripe(Request $request, PanierRepository $panierRepository, CommandeRepository $commandeRepository)
    {

        // $commandes = $request->query->get('commande');
        $session = $this->requestStack->getSession();
        $commandes = $session->get('commande');
        $total = $session->get('total');

        $currency = new CurrencyConvert();
        $total = round($currency->thmx_currency_convert($total, 'MAD', 'USD'));

        $user = $commandes->getUser(); //user lié à la commande
        // $key =  "sk_test_51LS3Z6B4pa87HJY2RvFzEsuMWP6lDNov4EbzLHNTMJqFE45FRf2609THNAuZbIusETmRUhLUneFiXgOID0Si4mL600t4o4uzur";
        $panier = $user->getPaniers();
        // dd($panier);
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY_TEST']);
        if ($this->getUser())
            $user = $this->getUser();
        $panier =  $user->getPaniers();
        $session = \Stripe\Checkout\Session::create([
            // $session = Session::create([
            // foreach($panier as $p) {
            //     foreach ($p->getProduit() as $prod) {
            //         $price = 0;
            //         $qte = $panierRepository->getQteProd($p->getId(), $prod->getId());
            //         // dd($qte[0]['qte_produit']);
            //         if ($prod->getNouveauPrix() != null) {
            //             $price = $prod->getNouveauPrix();
            //         } else {
            //             $price = $prod->getAncienPrix();
            //         }
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'commande N°' . $commandes->getId(),
                    ],
                    'unit_amount' => $total * 100,
                ],
                'quantity' => 1,
            ]],
            //     }
            // }
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' =>  $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
    }



    #[Route('/success-url', name: 'success_url', methods: ['GET', 'POST'])]
    public function facture(Request $request,MailerInterface $mailer ,PdfService $pdf, QuantiteRepository $q, ProduitRepository $produitRepository, PanierRepository $panierRepository, UserRepository $userRepository, CommandeRepository $commandeRepository): Response
    {
        // $cookie = $request->cookies->get('val');


        $session = $this->requestStack->getSession();
        $commandes = $session->get('commande');
        // $res = json_decode($cookie);
        // $cmd = $commandeRepository->find($commandes);

        // dd($res->status);
        // if ($res->status == "COMPLETED") {


        if ($this->getUser()) {

            $user = $this->getUser();
            $email = $request->get('email');
            $email = (new Email())
                ->from('oussabitarek123@gmail.com')
                ->to($user->getEmail())
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('réinitialisez votre mot de passe!')
                ->html(`<p>votre commande a été prise en considération la livraison est dans un délai maximal d'une semaine.</p>`);
            $this->json($mailer->send($email));

            // dd($user->getId());
            // dd($user->getPaniers());
            $array = [];
            $user = $userRepository->find($user->getId());
            $panier =  $user->getPaniers();

            foreach ($panier as $p) {
                // dd($panier);
                // dd($p->getId());

                foreach ($p->getProduit() as $prod) {
                    // dd($prod->getId());
                    $total = 0;
                    // foreach($panierRepository->selectPanierProd($panier->))
                    $qte = $panierRepository->getQteProd($p->getId(), $prod->getId());
                    // dd($qte[0]['qte_produit']);
                    // dd($prod);
                    if ($prod->getNouveauPrix() != null) {
                        $total = $qte[0]['qte_produit'] * $prod->getNouveauPrix();
                    } else {
                        $total = $qte[0]['qte_produit'] * $prod->getAncienPrix();
                    }

                    $var = $panierRepository->getVariationsProduits($p->getId(), $prod->getId());

                    $variations = str_replace("[\"", "", $var[0]['variations']);
                    $variations = str_replace("\"]", "", $variations);
                    $variations = str_replace("\"", "", $variations);
                    // dd($variations);
                    $array = [];
                    $array = explode(',', $variations);
                    $array = json_encode($array);

                    $qteConcerne =  $q->findquantite_produit($array, $prod->getId());
                    // dd($qteConcerne[0]['variations']);
                    // dd(explode('', $var[0]['variations']));
                    if ($prod->getType() === 'variable') {

                        if ($qteConcerne[0]['variations'] === $var[0]['variations']) {
                            $q->UpdateProduit($qteConcerne[0]['id'], $prod->getId(), $qte[0]['qte_produit']);
                            // dd($q);
                        }
                    } else {
                        $produitRepository->UpdateProduit($prod->getId(), $qte[0]['qte_produit']);
                    }

                    // ajouter le produit dans la table commande_produit
                    $commandeRepository->ajout_produit($commandes->getId(), $prod->getId(), $qte[0]['qte_produit'], $total, $prod->getDesignation());
                    $commandes->setStatus('traitées');
                    $commandeRepository->add($commandes, true);
                    $p->removeProduit($prod);

                    $panierRepository->RemoveProd($p->getId(), $prod->getId());
                }
            }
        }


        $facture = $commandeRepository->getFacture($commandes->getId());
        return $this->render('payment/success.html.twig', [
            'factures' => $facture,
            'commandes' => $commandes
        ]);
    }

    #[Route('/pdf', name: 'app_pdf', methods: ['GET', 'POST'])]
    public function pdfFacture(Request $request, PdfService $pdf, ProduitRepository $produitRepository, PanierRepository $panierRepository, UserRepository $userRepository, CommandeRepository $commandeRepository)
    {
        $session = $this->requestStack->getSession();
        $commandes = $session->get('commande');

        $facture = $commandeRepository->getFacture($commandes->getId());
        // dd($facture);
        $html = $this->render('payment/facture.html.twig', [
            'commandes' => $commandes,
            'factures' => $facture
        ]);
        $pdf->showPdfFile($html);
    }


    #[Route('/cash', name: 'app_cash', methods: ['GET', 'POST'])]
    public function cash(Request $request, CommandeRepository $commandeRepository): Response
    {
        $session = $this->requestStack->getSession();
        $commandes = $session->get('commande');
        $total = $session->get('total');

        $request->cookies->set('total', $total);

        $user = $commandes->getUser(); //user lié à la commande
        //  dd($user); // panier de ce user

        $panier = $user->getPaniers();
        // dd($panier);
        return $this->renderForm('payment/cash.html.twig', [
            'commandes' => $commandes,
            'panier' => $panier,
            'total' => $total
        ]);
    }

    #[Route('/cancel-url', name: 'cancel_url', methods: ['GET', 'POST'])]
    public function cancelUrl(CommandeRepository $commandeRepository): Response
    {

        $session = $this->requestStack->getSession();
        $commandes = $session->get('commande');
        $commandes->setStatus('annulées');
        $commandeRepository->add($commandes, true);
        return $this->render('payment/cancel.html.twig');
    }
}