<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Repository\SousCategorieRepository;
use App\Services\UploadFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin/categorie')]
#[Security("is_granted('ROLE_ADMIN')")]
class CategorieController extends AbstractController
{
    #[Route('/', name: 'app_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {

        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }


    #[Route('/aside', name: 'app_categorie_index_aside', methods: ['GET'])]
    public function indexaside(CategorieRepository $categorieRepository): Response
    {

        return $this->render('categorie/index_aside.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }









    #[Route('/all', name: 'app_categorie_all', methods: ['GET'])]
    public function all(CategorieRepository $categorieRepository, SerializerInterface $serializer): JsonResponse
    {

        $categories = $serializer->serialize($categorieRepository->findAll(), 'json', ['groups' => ['categorie:read']]);

        $json = json_decode($categories);

        return $this->json($json);
    }
    #[Route('/sous_categorie/{id}', name: 'app_sous_categorie_get', methods: ['GET'])]
    public function sous_Categorie(Categorie $categorie, SousCategorieRepository $sousCategorieRepository, SerializerInterface $serializer): JsonResponse
    {

        $souscategories = $serializer->serialize($sousCategorieRepository->findBy(['categorie' => $categorie]), 'json', ['groups' => ['souscategorie:read']]);

        $json = json_decode($souscategories);

        return $this->json($json);
    }



    #[Route('/corbeille', name: 'app_categorie_corbeille', methods: ['GET'])]
    public function corbeille(CategorieRepository $categorieRepository, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');

        $categories = $categorieRepository->findcorbeille();


        return $this->render('categorie/corbeille.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/restore/{id}', name: 'app_categorie_restore', methods: ['GET'])]
    public function restore($id, CategorieRepository $categorieRepository, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->getFilters()->disable('softdeleteable');
        $categorie = $categorieRepository->find($id);
        $categorie->setDeletedAt(Null);
        $em->persist($categorie);
        $em->flush();
        $categories = $categorieRepository->findcorbeille();
        return $this->render('categorie/corbeille.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/delete_from_corbeille/{id}', name: 'app_categorie_delete_fromcorbeille', methods: ['GET'])]
    public function deleteforce($id, CategorieRepository $categorieRepository, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        //  $em->getFilters()->disable('softdeleteable');
        //  $user=$userRepository->find($id);
        $categorieRepository->deletefromtrash($id);

        $categories = $categorieRepository->findcorbeille();
        return $this->render('categorie/corbeille.html.twig', [
            'categories' => $categories
        ]);
    }


    #[Route('/new/aside', name: 'app_categorie_new_aside', methods: ['GET', 'POST'])]
    public function newaside(Request $request, CategorieRepository $categorieRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->add($categorie, true);

            $this->addFlash('success', 'Categorie ajoute avec succes vous pouvez ajoute les sous categories');
            return $this->redirectToRoute('app_categorie_show', ['id' => $categorie->getId()]);
            // return $this->redirectToRoute('app_sous_categorie_new_aside', ['id' => $categorie->getId()]);
        }

        return $this->renderForm('categorie/new_aside.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }









    #[Route('/new', name: 'app_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieRepository $categorieRepository, SluggerInterface $slugger): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fichier = $form->get('photo')->getData();
            /** @var UploadedFile $file */
            if ($fichier) {
                $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fichier->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fichier->move(
                        $this->getParameter('categorie_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $categorie->setIcone($newFilename);
            }
            $categorieRepository->add($categorie, true);

            return $this->redirectToRoute('app_sous_categorie_new_variable', ['id' => $categorie->getId()]);
        }

        return $this->renderForm('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
            'sous_categories' => $categorie->getSousCategories()
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fichier = $form->get('photo')->getData();
            /** @var UploadedFile $file */
            if ($fichier) {
                $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fichier->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fichier->move(
                        $this->getParameter('categorie_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $categorie->setIcone($newFilename);
            }
            $categorieRepository->add($categorie, true);
            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }





    #[Route('/{id}/edit/aside', name: 'app_categorie_edit_aside', methods: ['GET', 'POST'])]
    public function editaside(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorieRepository->add($categorie, true);
            $this->addFlash('success', 'Categorie Modifiee avec succes');
            return $this->redirectToRoute('app_categorie_show', ['id' => $categorie->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie/_form_edit_aside.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }









    #[Route('/{id}', name: 'app_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('_token'))) {
            $categorieRepository->remove($categorie, true);
        }
        $this->addFlash('suppression', 'Categorie supprime avec succes');

        return $this->redirectToRoute('app_categorie_show', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/{id}', name: 'app_categorie_delete_get', methods: ['GET'])]
    public function deleteget(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        // if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('_token'))) {
        $categorieRepository->remove($categorie, true);

        $this->addFlash('suppression', 'Categorie supprime avec succes');

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete/group', name: 'app_categorie_delete_group', methods: ['POST'])]
    public function deletegroup(Request $request, CategorieRepository $categorieRepository): Response
    {

        // dd($request->get('check1'));
        $array = [];
        foreach ($categorieRepository->findAll() as $categorie) {
            if ($request->get('check' . $categorie->getId()) != null) {

                array_push($array, $categorie->getId());
            }
        }
        foreach ($array as $categorie) {
            $categorieRepository->remove($categorieRepository->find($categorie), true);
        }
        // if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
        // $userRepository->remove($user, true);
        $this->addFlash('suppression', 'La suppression est effectue  avec succes');

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}