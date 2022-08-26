<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Produit;
use App\Form\ImageType;
use App\Form\ImageVariableType;
use App\Repository\ImageRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('admin/image')]
class ImageController extends AbstractController
{
    #[Route('/', name: 'app_image_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }
    #[Route('/variable/{id}', name: 'app_image_index_variable', methods: ['GET'])]
    public function indexvariable(Produit $produit,ImageRepository $imageRepository): Response
    {
        return $this->render('image/index_variable.html.twig', [
            'images' => $imageRepository->findBy(['produit' => $produit ]),
            'produit' => $produit
        ]);
    }




    #[Route('/new/variable/produit/{id}', name: 'app_image_new_variable_produiut', methods: ['GET', 'POST'])]
    public function new_variable_produit( $id , Request $request,ProduitRepository $produitRepository ,ImageRepository $imageRepository, SluggerInterface $slugger): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageVariableType::class, $image , array('id' => $id ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
          
            $keys = array_keys( $request->get('image_variable') );
      
            // dd($request->get('image_variable'));
            // dd($request->get('image_variable'));    
        //    print_r($keys);
        //    dd($request->get('image_variable')[$keys[0]]);
        //    dd($form->get('photo'));
            // dd($form);
            /** @var UploadedFile $brochureFile */
            $image = $form->getData();
            $brochureFile = $form->get('photo')->getData();
            
            // dd($brochureFile);
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $image->setUrl($newFilename);
            }
            $imageRepository->add($image, true);
            $this->addFlash('success', 'Image ajoute avec succes');
            return $this->redirectToRoute('app_produit_show', ['id' => $id]);
        }

        return $this->renderForm('image/new_produit.html.twig', [
            'image' => $image,
            'form' => $form,
            'produit'=> $produitRepository->find($id)
        ]);
    }



    #[Route('/new/variable/{id}', name: 'app_image_new_variable', methods: ['GET', 'POST'])]
    public function new_variable( $id , Request $request,ProduitRepository $produitRepository ,ImageRepository $imageRepository, SluggerInterface $slugger): Response
    {
        $image = new Image();
        
        $form = $this->createForm(ImageVariableType::class, $image , array('id' => $id ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Image ajoute avec succes');

            // dd($form);
            /** @var UploadedFile $brochureFile */
            $image = $form->getData();
            $brochureFile = $form->get('photo')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $image->setUrl($newFilename);
            }
            $imageRepository->add($image, true);
            return $this->redirectToRoute('app_image_new_variable', ['id' => $id]);
        }

        return $this->renderForm('image/new.html.twig', [
            'image' => $image,
            'form' => $form,
            'produit'=> $produitRepository->find($id)
        ]);
    }













    #[Route('/new', name: 'app_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImageRepository $imageRepository, SluggerInterface $slugger): Response
    {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // dd($form);
            /** @var UploadedFile $brochureFile */
            $image = $form->getData();
            $brochureFile = $form->get('photo')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $image->setUrl($newFilename);
            }
            $imageRepository->add($image, true);
            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('image/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_image_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image, ImageRepository $imageRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {

            // dd($form);
            /** @var UploadedFile $brochureFile */
            $image = $form->getData();
            $brochureFile = $form->get('photo')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('picture_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $image->setUrl($newFilename);
            }


            $imageRepository->add($image, true);

            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }


    #[Route('/variable/edit/{id}/{slug}', name: 'app_image_edit_variable', methods: ['GET', 'POST'])]
    public function editvariable($id,$slug,Request $request,ProduitRepository $produitRepository ,ImageRepository $imageRepository, SluggerInterface $slugger): Response
    {
        $image=$imageRepository->find($id);
       
        
        $form = $this->createForm(ImageVariableType::class, $image,array('id' => $slug ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           

    
            // dd($form);
            /** @var UploadedFile $brochureFile */
            $image = $form->getData();
            $brochureFile = $form->get('photo')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $image->setUrl($newFilename);
            }
            $imageRepository->add($image, true);
            $this->addFlash('success', 'Vos modifications sont enregistres avec succes');


            return $this->redirectToRoute('app_produit_show', ['id'=> $slug]);
        }

        return $this->renderForm('image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
            'produit'=>$produitRepository->find($slug),
        ]);
    }


    // #[Route('/{id}/{slug}', name: 'app_image_delete', methods: ['POST'])]
    // public function delete($id ,$slug,Request $request, ImageRepository $imageRepository): Response
    // {
    //     $image=$imageRepository->find($id);
    //     $this->addFlash('suppression', 'l element est supprime  avec succes');

    //     if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
    //         $imageRepository->remove($image, true);
    //     }

    //     return $this->redirectToRoute('app_produit_show', ['id' => $slug ]);
    // }

    #[Route('/{id}/{slug}', name: 'app_image_delete_get', methods: ['GET'])]
    public function deleteget($id ,$slug,Request $request, ImageRepository $imageRepository): Response
    {
        $image=$imageRepository->find($id);
       

        // if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
            $imageRepository->remove($image, true);
            $this->addFlash('suppression', 'l element est supprime  avec succes');

        return $this->redirectToRoute('app_produit_show', ['id' => $slug ]);
    }

    #[Route('/delete/group', name: 'app_image_delete_group', methods: ['POST'])]
    public function deletegroup(Request $request, ImageRepository $imageRepository): Response
    {
        // dd($request->get('check1'));
        $array=[];
        foreach($imageRepository->findAll() as $image){
          if($request->get('check'.$image->getId())!=null){
           
             array_push($array,$image->getId());
          }

        }
        foreach($array as $image){
            $imageRepository->remove($imageRepository->find($image),true);
        }
        // if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // $userRepository->remove($user, true);
            $this->addFlash('suppression', 'La suppression est effectue  avec succes');

        return $this->redirectToRoute('app_produit_show', ['id' =>$request->get('produit') ], Response::HTTP_SEE_OTHER);
    }
}