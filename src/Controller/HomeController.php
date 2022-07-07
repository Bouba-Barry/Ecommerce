<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class HomeController extends AbstractController
{
    #[Route('', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
