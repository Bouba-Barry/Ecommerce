<?php

namespace App\Controller;

use App\Services\UploadFile;
use App\Entity\SousCategorie;
use App\Form\SousCategorieType;
use App\ImageOptimizer;
use App\Repository\SousCategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Security("is_granted('ROLE_ADMIN')")]
#[Route('/admin/sous/categorie')]
class SousCategorieController extends AbstractController
{
    #[Route('/', name: 'app_sous_categorie_index', methods: ['GET'])]
    public function index(SousCategorieRepository $sousCategorieRepository): Response
    {
        return $this->render('sous_categorie/index.html.twig', [
            'sous_categories' => $sousCategorieRepository->findAll(),
        ]);
    }


    #[Route('/new', name: 'app_sous_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger, SousCategorieRepository $sousCategorieRepository): Response
    {
        $sousCategorie = new SousCategorie();
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */

            // // $fileUploader->targetDirectory = 'sous_categorie_directory';
            // $fileUploader = new UploadFile('sous_categorie_directory', $slugger);
            $brochureFile = $form->get('photo')->getData();
            // if ($file) {
            //     $FileName = $fileUploader->upload($file);
            //     $sousCategorie->setPicture($FileName);
            // }
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('sous_categorie_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $sousCategorie->setPicture($newFilename);
            }


            $sousCategorieRepository->add($sousCategorie, true);


            return $this->redirectToRoute('app_sous_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sous_categorie/new.html.twig', [
            'sous_categorie' => $sousCategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sous_categorie_show', methods: ['GET'])]
    public function show(SousCategorie $sousCategorie): Response
    {
        return $this->render('sous_categorie/show.html.twig', [
            'sous_categorie' => $sousCategorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sous_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SousCategorie $sousCategorie, SousCategorieRepository $sousCategorieRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(SousCategorieType::class, $sousCategorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */

            // $fileUploader->setTargetDirectory('sous_categorie_directory');
            $brochureFile = $form->get('photo')->getData();
            // if ($file) {
            //     $FileName = $fileUploader->upload($file);
            //     $sousCategorie->setPicture($FileName);
            // }
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('sous_categorie_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $sousCategorie->setPicture($newFilename);
                $fileName = $this->getParameter('sous_categorie_directory') . $newFilename;
                // dd($fileName);
                // $imageOptimizer->resize($this->getParameter('sous_categorie_directory') . $newFilename, $newFilename);
            }
            $sousCategorieRepository->add($sousCategorie, true);

            return $this->redirectToRoute('app_sous_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sous_categorie/edit.html.twig', [
            'sous_categorie' => $sousCategorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sous_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, SousCategorie $sousCategorie, SousCategorieRepository $sousCategorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sousCategorie->getId(), $request->request->get('_token'))) {
            $sousCategorieRepository->remove($sousCategorie, true);
        }

        return $this->redirectToRoute('app_sous_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}