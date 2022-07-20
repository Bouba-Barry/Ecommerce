<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AdminProfileType;
use App\Form\EditPasswordType;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_ADMIN')  or is_granted('ROLE_COMPTABLE')")]
#[Route('/admin/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/profile', name: 'app_user_profile')]
    public function profile(Request $request, SluggerInterface $slugger, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        
        $user = $this->getUser();
        
        //  dd($user->getTelephone());
        // $user = $this->getUser();
        // dd($user);
        // $user = new User();
        $form = $this->createForm(AdminProfileType::class, $user);
        // $form->get('telephone')->setData('0'.$user->getTelephone());
        // $form->setTelephone("0");
        $form2 = $this->createForm(EditPasswordType::class, $user);
        // $form2->get('telephone')->setData('0'.$user->getTelephone());
        if($form){
        $form->handleRequest($request);}
        if($form2){
        $form2->handleRequest($request);
        }

        // if (count($form2->getErrors()) > 0) {
        //     dd($form2->getErrors());
        // }

        if($form2->isSubmitted() && $form2->isValid() ){
            // dd("dd");
            $this->addFlash('success', 'Mot de passe a ete modifie avec succes');
            $user = $form2->getData();
            // dd($user->getPassword());
            //   dd($user->getPassword());

            // this condition is needed because the 'brochure' field is not required
            // so the img must be processed only when a file is uploaded
       
            /** fin de l'upload du profile du user */

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);
           

            $userRepository->add($user, true);
            
            return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);


        }
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Vos informations sont modifies avec success');
             

            /** @var UploadedFile $picture */
            $user = $form->getData();
            $picture = $form->get('profile')->getData();
            //  dd($form->get('telephone')->getData());
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $picture->move(
                        $this->getParameter('profile_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setProfile($newFilename);
                // return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);

            }
            /** fin de l'upload du profile du user */


            // $hashedPassword = $passwordHasher->hashPassword(
            //     $user,
            //     $user->getPassword()
            // );

            // $user->setPassword($hashedPassword);
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('user/profile.html.twig', [
            'user' => $this->getUser(),
            'users'=>$userRepository->findAll(),
            'form' => $form,
            'form2' => $form2
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        //  if (count($form->getErrors()) > 0) {
        //      dd($form->getErrors());
        //  }
        // && $form->isValid()
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($user->getPassword());


            /** @var UploadedFile $picture */
            $user = $form->getData();
            $picture = $form->get('profile')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the img must be processed only when a file is uploaded
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $picture->move(
                        $this->getParameter('profile_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setProfile($newFilename);
            }
            /** fin de l'upload du profile du user */

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);


            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        // dd("HH");
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }


   
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SluggerInterface $slugger, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        // $form = $this->createForm(UserType::class, $user);
        // $form->handleRequest($request);
        // //  if (count($form->getErrors()) > 0) {
        // //      dd($form->getErrors());
        // //  }
        // if ($form->isSubmitted() && $form->isValid()) {

        //     /** @var UploadedFile $picture */
        //     $user = $form->getData();
        //     $picture = $form->get('profile')->getData();

        //     // this condition is needed because the 'brochure' field is not required
        //     // so the PDF file must be processed only when a file is uploaded
        //     if ($picture) {
        //         $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
        //         // this is needed to safely include the file name as part of the URL
        //         $safeFilename = $slugger->slug($originalFilename);
        //         $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

        //         // Move the file to the directory where pictures are stored
        //         try {
        //             $picture->move(
        //                 $this->getParameter('profile_directory'),
        //                 $newFilename
        //             );
        //         } catch (FileException $e) {
        //             // ... handle exception if something happens during file upload
        //         }

        //         // updates the 'brochureFilename' property to store the PDF file name
        //         // instead of its contents
        //         $user->setProfile($newFilename);
        //     }
        //     /** fin de l'upload du profile du user */


        //     $hashedPassword = $passwordHasher->hashPassword(
        //         $user,
        //         $user->getPassword()
        //     );

        //     $user->setPassword($hashedPassword);
        //     $userRepository->add($user, true);

        //     return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        // }

        // return $this->renderForm('user/edit.html.twig', [
        //     'user' => $user,
        //     'form' => $form,
        // ]);
        // dd("jhh");

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $picture */
            $user = $form->getData();
            $picture = $form->get('profile')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $picture->move(
                        $this->getParameter('profile_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setProfile($newFilename);

            }
            /** fin de l'upload du profile du user */


            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );

            $user->setPassword($hashedPassword);
            $userRepository->add($user, true);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
                 'user' => $user,
                 'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        //  dd("ff");
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}