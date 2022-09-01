<?php

namespace App\Controller;

use App\Entity\Lien;
use App\Entity\Slide;
use App\Form\LienType;
use App\Repository\LienRepository;
use App\Repository\SlideRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/lien')]
class LienController extends AbstractController
{
    #[Route('/', name: 'app_lien_index', methods: ['GET'])]
    public function index(LienRepository $lienRepository): Response
    {
        return $this->render('lien/index.html.twig', [
            'liens' => $lienRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_lien_new', methods: ['GET', 'POST'])]
    public function new($id,Request $request, SluggerInterface $slugger,LienRepository $lienRepository): Response
    {
        $lien = new Lien();
        $form = $this->createForm(LienType::class, $lien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('image')->getData();
           
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $picture->move(
                        $this->getParameter('slideDirectory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $lien->setImage($newFilename);
                // return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);

            }


            $lienRepository->add($lien, true);
            $this->addFlash('success', 'Lien Ajoute avec succes');
            return $this->redirectToRoute('app_slide_show', ['id' => $id ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lien/new.html.twig', [
            'lien' => $lien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lien_show', methods: ['GET'])]
    public function show(Lien $lien): Response
    {
        return $this->render('lien/show.html.twig', [
            'lien' => $lien,
        ]);
    }

    #[Route('/{id}/edit/{slug}', name: 'app_lien_edit', methods: ['GET', 'POST'])]
    public function edit($id,$slug,Request $request,SluggerInterface $slugger ,LienRepository $lienRepository): Response
    {
        $lien=$lienRepository->find($id);
        $form = $this->createForm(LienType::class, $lien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('image')->getData();
           
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $picture->move(
                        $this->getParameter('slideDirectory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $lien->setImage($newFilename);
                // return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);

            }





            $lienRepository->add($lien, true);
            $this->addFlash('success', 'lien modifie avec succes');
            return $this->redirectToRoute('app_slide_show', ['id' => $slug ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lien/edit.html.twig', [
            'lien' => $lien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lien_delete', methods: ['POST'])]
    public function delete(Request $request, Lien $lien, LienRepository $lienRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lien->getId(), $request->request->get('_token'))) {
            $lienRepository->remove($lien, true);
        }

        return $this->redirectToRoute('app_lien_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{id}/{slug}', name: 'app_lien_delete_get', methods: ['GET'])]
    public function deleteget($id,$slug,Request $request, LienRepository $lienRepository): Response
    {
        // if ($this->isCsrfTokenValid('delete' . $reduction->getId(), $request->request->get('_token'))) {
            $lien=$lienRepository->find($id);
            $lienRepository->remove($lien, true);
            $this->addFlash('suppression', 'lien supprime avec succes');

        return $this->redirectToRoute('app_slide_show', ['id' => $slug ], Response::HTTP_SEE_OTHER);
    }


    #[Route('/delete/group', name: 'app_lien_delete_group', methods: ['POST'])]
    public function deletegroup(Request $request,LienRepository $lienRepository,SlideRepository $slideRepository): Response
    {
        // dd($request->get('check1'));
        $array=[];
        foreach($lienRepository->findAll() as $lien){
          if($request->get('check'.$lien->getId())!=null){
           
             array_push($array,$lien->getId());
          }

        }
        foreach($array as $lien){
            $lienRepository->remove($lienRepository->find($lien),true);
        }
        // if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // $userRepository->remove($user, true);
            $this->addFlash('suppression', 'La suppression est effectue  avec succes');

        return $this->redirectToRoute('app_slide_show', ['id' => $request->get('slide') ], Response::HTTP_SEE_OTHER);
    }







}
