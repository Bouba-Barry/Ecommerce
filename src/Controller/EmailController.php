<?php

namespace App\Controller;

use App\Entity\Email;
use App\Form\EmailType;
use App\Repository\EmailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/email')]
class EmailController extends AbstractController
{
    #[Route('/', name: 'app_email_index', methods: ['GET'])]
    public function index(EmailRepository $emailRepository): Response
    {
        $mail = null;
        if (in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            $mail = $emailRepository->findEmailByAdmin($this->getUser(), 'super_admin');
        } else {
            $mail = $emailRepository->findEmailByAdmin($this->getUser(), '');
        }
        // dd($mail);
        $size = count($mail);
        return $this->render('email/index.html.twig', [
            'emails' => $mail,
            'size' => $size
        ]);
    }

    #[Route('/new', name: 'app_email_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmailRepository $emailRepository): Response
    {
        $email = new Email();
        $form = $this->createForm(EmailType::class, $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailRepository->add($email, true);

            return $this->redirectToRoute('app_email_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('email/new.html.twig', [
            'email' => $email,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_email_show', methods: ['GET'])]
    public function show(Email $email, EmailRepository $emailRepository): Response
    {
        $mail = null;
        if (in_array("ROLE_SUPER_ADMIN", $this->getUser()->getRoles())) {
            $mail = $emailRepository->findEmailByAdmin($this->getUser(), 'super_admin');
        } else {
            $mail = $emailRepository->findEmailByAdmin($this->getUser(), '');
        }
        $size = count($mail);

        return $this->render('email/show.html.twig', [
            'email' => $email,
            'size' => $size
        ]);
    }

    #[Route('/{id}/edit', name: 'app_email_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Email $email, EmailRepository $emailRepository): Response
    {
        $form = $this->createForm(EmailType::class, $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailRepository->add($email, true);

            return $this->redirectToRoute('app_email_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('email/edit.html.twig', [
            'email' => $email,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_email_delete', methods: ['POST'])]
    public function delete(Request $request, Email $email, EmailRepository $emailRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $email->getId(), $request->request->get('_token'))) {
            $emailRepository->remove($email, true);
        }

        return $this->redirectToRoute('app_email_index', [], Response::HTTP_SEE_OTHER);
    }
}