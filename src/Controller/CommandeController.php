<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\VilleRepository;
use Doctrine\Common\Collections\Expr\Value;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Cookie;

#[Route('/commande')]
// #[Security("is_granted('ROLE_ADMIN')")]
class CommandeController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new')]
    public function new(Request $request, SerializerInterface $serializer,VilleRepository $villeRepository ,CommandeRepository $commandeRepository): Response
    {
        $commande = new Commande();
        $user = $this->getUser();

        // $region =  
        $route = "";
        // $submit = $request->get('submit');
        if ($_POST) {
            // dd("its ok");
            $adresse = $request->get('other_adresse');
            $ville=$request->get("ville");
            
            $payment = $request->get('choice', 0)[0];
            if ($adresse && strlen($adresse) > 0) {
                $commande->setAdresseLivraison($adresse);
            } else {
                $commande->setAdresseLivraison($request->get('adresse'));
            }
            if($ville && strlen($ville) > 0){
                $commande->setVille($villeRepository->find($ville));
       
            }

            // dd($payment);
            $total = $request->get('total');
            // dd($total);
            $commande->setMethodPayement($payment);
            $commande->setUser($user);

            $commandeRepository->add($commande, true);
            // dd($commande);
            $produits = $commande->getProduit();


            $session = $this->requestStack->getSession();
            $session->set('commande', $commande);
            $session->set('total', $total);

            switch ($payment) {
                case 'paypal':
                    $route = "app_paypal";
                    break;
                case 'cash':
                    $route = "app_cash";
                    break;
                case 'bank':
                    $route = "app_bank";
                    break;
            }
            // $array = [
            //     'commande' => $commande,
            // ];

            // $json = $serializer->serialize($commande, 'json', ['groups' => ['cmd:read', 'prod:read']]);
            // $json = json_decode($json);
            // dd($json);

            return $this->redirectToRoute($route, ['produits' => $produits], Response::HTTP_SEE_OTHER);

            // return $this->render('payment/paypal.html.twig', ['commande' => $json, 'produits' => $produits]);
        }
        $msg = "Vérifiez Vos champs SVP";
        // $response = new Response();
        // $response->setContent(json_encode([
        //     'data' => 123,
        // ]));
        // $response->headers->set('Content-Type', 'application/json');
        return $this->render('frontend/checkout.html.twig', [
            'message' => $msg,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->add($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande, true);
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}