<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Component\Pager\PaginatorInterface;

use App\Repository\ContactRepository;
use App\Entity\Contact;

use App\Form\ContactType;


class ContactController extends AbstractController
{
    #[Route('/', name: 'contacts_index', methods: ['GET'])]
    public function index(Request $request, ContactRepository $contactRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->get('page', 1);
        $contacts = $contactRepository->findAll();

        $pagination = $paginator->paginate(
            $contacts,
            $page,
            2
        );

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'pagination' => $pagination
        ]);
    }

    #[Route('/contacts', name: 'contact_new', methods: ['GET','POST'])]
    public function new(Request $request, ContactRepository $contactRepository): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $contactRepository->save($contact, true);

            return $this->redirectToRoute('contacts_index');
        }

        return $this->renderForm('contact/new.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form,
        ]);
    }



    #[Route('/contacts/{slug}', name: 'contact_edit', methods: ['GET','POST'])]
    public function show($slug, Request $request, ContactRepository $contactRepository): Response
    {
        $contact = $contactRepository->findOneBy(['slug' => $slug]);

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $contactRepository->save($contact, true);

            return $this->redirectToRoute('contacts_index');
        }

        return $this->renderForm('contact/edit.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form,
        ]);  
    }

    #[Route('/contacts/{slug}/description', name: 'contact_description', methods: ['GET'])]
    public function description($slug, Request $request, ContactRepository $contactRepository): Response
    {
        $contact = $contactRepository->findOneBy(['slug' => $slug]);

        return $this->renderForm('_modalContent.html.twig', [
            'controller_name' => 'ContactController',
            'content' => $contact->getDescription(),
        ]);  
    }

    #[Route('/contacts/{slug}/delete', name: 'contact_delete', methods: ['GET'])]
    public function delete($slug, Request $request, ContactRepository $contactRepository): Response
    {
        $contact = $contactRepository->findOneBy(['slug' => $slug]);
        $contactRepository->remove($contact, true);

        return $this->redirectToRoute('contacts_index');
    }
}
