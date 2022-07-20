<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// #[Security("is_granted('ROLE_USER')")]
class LoginController extends AbstractController
{
  #[Route('/login', name: 'app_login')]
  public function index(AuthenticationUtils $authenticationUtils): Response
  {
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();
  //   $plaintext_password = "tarek";

//   $crypted = password_hash('tarek', PASSWORD_DEFAULT);
// dd($crypted);
  // // The hashed password retrieved from database
  // $hash = 
  //   '$2y$10$He5nuFPBPHbVYaaXavsVu.zbsIyILENNAiOz6wB.fVl0qWh43Rb3O';
  
  // // Verify the hash against the password entered
  // $verify = password_verify($plaintext_password, $hash);
  
  // // Print the result depending if they match
  // if ($verify) {
  //     dd('Password Verified!');
  // } else {
  //     dd('Incorrect Password!');
  // } 



       if ($error ) {
              dd($error);
          }
    return $this->render('login/index.html.twig', [
      'last_username' => $lastUsername,
      'error'         => $error,  
    ]);
  }
 

  /**
   * @Route("/admin/logout", name="app_logout", methods={"GET"})
   */
  public function logout(): Response
  {
    // controller can be blank: it will never be called!
    return $this->render('home/index.html.twig');
  }
}