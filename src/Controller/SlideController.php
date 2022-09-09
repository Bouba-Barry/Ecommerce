<?php

namespace App\Controller;

use App\Entity\Slide;
use App\Form\SlideType;
use App\Repository\LienRepository;
use App\Repository\SlideRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/slide')]
class SlideController extends AbstractController
{
    #[Route('/', name: 'app_slide_index', methods: ['GET'])]
    public function index(SlideRepository $slideRepository): Response
    {
        return $this->render('slide/index.html.twig', [
            'slides' => $slideRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_slide_new', methods: ['GET', 'POST'])]
    public function new(Request $request,SluggerInterface $slugger  ,SlideRepository $slideRepository): Response
    {
        $slide = new Slide();
        $form = $this->createForm(SlideType::class, $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('video')->getData();
           
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $picture->move(
                        $this->getParameter('videoDirectory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $slide->setVideo($newFilename);
                // return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);

            }

            $slide->setChoisi("non");
            $slideRepository->add($slide, true);
            $this->addFlash('success', 'Slide modifie avec succes');
            return $this->redirectToRoute('app_slide_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slide/new.html.twig', [
            'slide' => $slide,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_slide_show', methods: ['GET'])]
    public function show(Slide $slide,LienRepository $lienRepository): Response
    {
        return $this->render('slide/show.html.twig', [
            'slide' => $slide,
            'liens'=>$lienRepository->findBy(['slide'=>$slide]),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_slide_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Slide $slide,SluggerInterface $slugger  ,SlideRepository $slideRepository): Response
    {
        $form = $this->createForm(SlideType::class, $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('video')->getData();
           
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $picture->move(
                        $this->getParameter('videoDirectory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $slide->setVideo($newFilename);
                // return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);

            }


            $slideRepository->add($slide, true);
            $this->addFlash('success', 'Slide modifie avec succes');
            return $this->redirectToRoute('app_slide_edit', ['id' => $slide->getId()  ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slide/edit.html.twig', [
            'slide' => $slide,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_slide_delete', methods: ['POST'])]
    public function delete(Request $request, Slide $slide, SlideRepository $slideRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slide->getId(), $request->request->get('_token'))) {
            $slideRepository->remove($slide, true);
        }
        $this->addFlash('suppression', 'slide supprime avec succes');
        return $this->redirectToRoute('app_slide_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{id}', name: 'app_slide_delete_get', methods: ['GET'])]
    public function deleteget(Request $request, Slide $slide, SlideRepository $slideRepository): Response
    {
        // if ($this->isCsrfTokenValid('delete' . $reduction->getId(), $request->request->get('_token'))) {
            $slideRepository->remove($slide, true);
            $this->addFlash('suppression', 'slide supprime avec succes');

        return $this->redirectToRoute('app_slide_index', [], Response::HTTP_SEE_OTHER);
    }



    #[Route('/delete/group', name: 'app_slide_delete_group', methods: ['POST'])]
    public function deletegroup(Request $request,SlideRepository $slideRepository): Response
    {
        // dd($request->get('check1'));
        $array=[];
        foreach($slideRepository->findAll() as $slide){
          if($request->get('check'.$slide->getId())!=null){
           
             array_push($array,$slide->getId());
          }

        }
        foreach($array as $slide){
            $slideRepository->remove($slideRepository->find($slide),true);
        }
        // if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // $userRepository->remove($user, true);
            $this->addFlash('suppression', 'La suppression est effectue  avec succes');

        return $this->redirectToRoute('app_slide_index', [], Response::HTTP_SEE_OTHER);
    }

}
