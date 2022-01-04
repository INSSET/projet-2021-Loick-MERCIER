<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Note;
use App\Form\Type\CustomerType;
use App\Form\Type\NoteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    #[Route('/customer/create', name: 'app_create_customer')]
    public function createCustomer(Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer->setName(strtoupper($customer->getName()));
            $customer->setFirstName(ucfirst($customer->getFirstName()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();
            return $this->redirectToRoute('app_customer', array('id' => $customer->getId()));
        }

        return $this->renderForm('form.html.twig', [
            'formName' => 'Create new customer',
            'form' => $form,
        ]);
    }

    #[Route('/customer/{id}', name: 'app_customer', requirements: ['id' => '\d+'])]
    public function customer(ManagerRegistry $doctrine, int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $customerRepository = $doctrine->getRepository(Customer::class);

        $customer = $customerRepository->find($id);

        if (!$customer) {
            return $this->render('customer/unknown.html.twig');
        }

        $lastNote = null;

        $notes = $doctrine->getRepository(Note::class)->findByCustomerId($id);
        if (count($notes) != 0){
            foreach ($notes as $note) {
                if (!$lastNote || $note->getDate() > $lastNote->getDate()) {
                    $lastNote = $note;
                }
            }
        }

        return $this->render('customer/information.html.twig', [
            'customer' => $customer,
            'note' => $lastNote,
        ]);
    }

    #[Route('/customer/edit/{id}', name: 'app_edit_customer', requirements: ['id' => '\d+'])]
    public function updateCustomer(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $customer = $doctrine->getRepository(Customer::class)->find($id);

        if (!$customer) {
            return $this->render('customer/unknown.html.twig');
        }

        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer->setName(strtoupper($customer->getName()));
            $customer->setFirstName(ucfirst($customer->getFirstName()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_customer', array('id' => $id));
        }

        return $this->renderForm('form.html.twig', [
            'formName' => 'Edit customer',
            'form' => $form,
        ]);
    }

    #[Route('/customer/{id}/note/add', name: 'app_add_note', requirements: ['id' => '\d+'])]
    public function updateNote(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $customer = $doctrine->getRepository(Customer::class)->find($id);

        if (!$customer) {
            return $this->render('customer/unknown.html.twig');
        }

        $note = new Note();
        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note->setDate(time());
            $note->setCustomer($customer);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($note);
            $entityManager->flush();
            return $this->redirectToRoute('app_customer', array('id' => $id));
        }

        return $this->renderForm('form.html.twig', [
            'formName' => 'Add note',
            'form' => $form,
        ]);
    }

    #[Route('/customer/{id}/notes', name: 'app_notes', requirements: ['id' => '\d+'])]
    public function notes(Request $request, ManagerRegistry $doctrine, int $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $customer = $doctrine->getRepository(Customer::class)->find($id);

        if (!$customer) {
            return $this->render('customer/unknown.html.twig');
        }

        $notes = $doctrine->getRepository(Note::class)->findByCustomerId($id);

        return $this->render('note/list.html.twig', [
            'customer' => $customer,
            'notes' => $notes,
        ]);
    }
}
