<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\Mapping\Id;
use App\Form\ProduitVariableType;
use App\Repository\ImageRepository;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use App\Repository\QuantiteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



#[Security("is_granted('ROLE_ADMIN')")]
#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, UserRepository $userRepository): Response
    {

        // dd($userRepository->findRecentClient());
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }



    #[Route('/corbeille', name: 'app_produit_corbeille', methods: ['GET'])]
    public function corbeille(ProduitRepository $produitRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        
        $produits=$produitRepository->findcorbeille();
        

        return $this->render('produit/corbeille.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/restore/{id}', name: 'app_produit_restore', methods: ['GET'])]
    public function restore($id,ProduitRepository $produitRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        $produit=$produitRepository->find($id);
        $produit->setDeletedAt(Null);
        $em->persist($produit);
        $em->flush();        
        $produits=$produitRepository->findcorbeille();
        return $this->render('produit/corbeille.html.twig', [
            'produits' => $produits
        ]);
    }

    #[Route('/delete_from_corbeille/{id}', name: 'app_produit_delete_fromcorbeille', methods: ['GET'])]
    public function deleteforce($id,ProduitRepository $produitRepository, ManagerRegistry $doctrine): Response
    {
        $em=$doctrine->getManager();
       
        //  $em->getFilters()->disable('softdeleteable');
        //  $user=$userRepository->find($id);
         $produitRepository->deletefromtrash($id);
             
        $produits=$produitRepository->findcorbeille();
        return $this->render('produit/corbeille.html.twig', [
            'produits' => $produits
        ]);
    }




    
    #[Route('/admin/new/variable', name: 'app_produit_new_variable', methods: ['GET', 'POST'])]
    public function newvariable(Request $request, ProduitRepository $produitRepository, SluggerInterface $slugger): Response
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitVariableType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

           
            // new  $attribut_produit = $form->get('attributs')->getData();

            // foreach ($attribut_produit as $var) {
            //     $produit->addAttribut($var);
            // new }

            // dd($form);
           
            $produit = $form->getData();
            $produit->setQteStock(0);
            $produit->setType("variable");
            

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
           


            // uploadImage($slugger, $produit, $brochureFile);


            $produitRepository->add($produit, true);
            $this->addFlash('success', 'Produit ajoute avec succes, vous pouvez ajouter les variations');

            return $this->redirectToRoute('app_produit_show', ['id' => $produit->getId() ]);
        }

        return $this->renderForm('produit/new_variable.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }


    
    #[Route('/admin/variable/{id}/edit', name: 'app_produit_edit_variable', methods: ['GET', 'POST'])]
    public function edit_variable(Request $request, Produit $produit, SluggerInterface $slugger, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitVariableType::class, $produit);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
             $attribut_produit = $form->get('attributs')->getData();

             foreach ($attribut_produit as $var) {
                 $produit->addAttribut($var);
             }
            
            
             
            //  dd($produit->getSousCategorie());
            // dd($form);
            
            $produit = $form->getData();
            
            $produitRepository->add($produit, true);
            $this->addFlash('success', 'vos modifications sont enregistre avec succes avec succes');
            return $this->redirectToRoute('app_produit_show', ['id'=>$produit->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit_variable.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }





















    #[Route('/admin/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository, SluggerInterface $slugger): Response
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            


            // new  $attribut_produit = $form->get('attributs')->getData();

            // foreach ($attribut_produit as $var) {
            //     $produit->addAttribut($var);
            // new }

            // dd($form);
            /** @var UploadedFile $brochureFile */
            $produit = $form->getData();
            $produit->setType("stable");
            $brochureFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setImageProduit($newFilename);
            }


            // uploadImage($slugger, $produit, $brochureFile);


            $produitRepository->add($produit, true);

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }


    #[Route('/admin/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit,ImageRepository $imageRepository,QuantiteRepository $quantiteRepository): Response
    {
        // if($produit->getUser()==$this->getUser()){
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'quantites' => $quantiteRepository->findBy(['produit' => $produit] ),
            'attributs' => $produit->getAttributs(),
            'images' => $imageRepository->findBy(['produit' => $produit ]),
        ]);
    }

    #[Route('/admin/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, SluggerInterface $slugger, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid() ) {

            // $attribut_produit = $form->get('attributs')->getData();

            // foreach ($attribut_produit as $var) {
            //     $produit->addAttribut($var);
            // }

            // dd($form);
            /** @var UploadedFile $brochureFile */
            $produit = $form->getData();
            $brochureFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setImageProduit($newFilename);
            }
           
            $produitRepository->add($produit, true);
            $this->addFlash('success', 'vos modifications sont enregistre avec succes avec succes');

            // dd("dfl");
            return $this->redirectToRoute('app_produit_show', ['id'=>$produit->getId() ]);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/admin/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
            $this->addFlash('suppression', 'Produit supprime avec succes');

        }


        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/admin/delete/{id}', name: 'app_produit_delete_get', methods: ['POST'])]
    public function deleteget(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        // if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
            $this->addFlash('suppression', 'Produit supprime avec succes');


        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/group', name: 'app_produit_delete_group', methods: ['POST'])]
    public function deletegroup(Request $request, ProduitRepository $produitRepository): Response
    {
        // dd($request->get('check1'));
        $array=[];
        foreach($produitRepository->findAll() as $produit){
          if($request->get('check'.$produit->getId())!=null){
           
             array_push($array,$produit->getId());
          }

        }
        foreach($array as $produit){
            $produitRepository->remove($produitRepository->find($produit),true);
        }
        // if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            // $userRepository->remove($user, true);
            $this->addFlash('suppression', 'La suppression est effectue  avec succes');

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }


}